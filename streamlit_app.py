import streamlit as st
import numpy as np
import pandas as pd
import plotly.graph_objects as go
import plotly.express as px
from datetime import datetime, timedelta
import json
import anthropic
import requests
from typing import Optional
import os

# Configuration de l'API OpenAI
from openai import OpenAI
if 'OPENAI_API_KEY' not in st.secrets:
    st.warning("⚠️ L'API key OpenAI n'est pas configurée. Certaines fonctionnalités seront limitées.")
    openai_client = None
else:
    openai_client = OpenAI(api_key=st.secrets['OPENAI_API_KEY'])

# Configuration de l'API Laravel
LARAVEL_API_URL = st.secrets.get('LARAVEL_API_URL', 'http://localhost:8000/api')
JWT_TOKEN = st.secrets.get('JWT_TOKEN', None)

def get_api_headers() -> dict:
    """Retourne les headers pour l'API Laravel avec JWT"""
    if JWT_TOKEN:
        return {
            'Authorization': f'Bearer {JWT_TOKEN}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    return {'Content-Type': 'application/json', 'Accept': 'application/json'}

def sync_with_laravel(endpoint: str, method: str = 'GET', data: Optional[dict] = None) -> dict:
    """Synchronise les données avec l'API Laravel"""
    try:
        url = f"{LARAVEL_API_URL}/{endpoint}"
        headers = get_api_headers()
        
        if method == 'GET':
            response = requests.get(url, headers=headers)
        elif method == 'POST':
            response = requests.post(url, headers=headers, json=data)
        elif method == 'PUT':
            response = requests.put(url, headers=headers, json=data)
        elif method == 'DELETE':
            response = requests.delete(url, headers=headers)
        
        response.raise_for_status()
        return response.json()
        
    except requests.exceptions.RequestException as e:
        st.error(f"Erreur de connexion à l'API : {str(e)}")
        return {}

def generate_meal_plan(profile: dict, preferences: dict) -> dict:
    """Génère un plan alimentaire personnalisé avec OpenAI"""
    if not openai_client:
        st.warning("⚠️ L'API OpenAI n'est pas configurée. Utilisation du plan par défaut.")
        return {}
    
    try:
        # Construire le prompt pour GPT
        prompt = f"""En tant qu'expert en nutrition, crée un plan alimentaire personnalisé avec ces critères :
        - Objectif : {profile['goal']}
        - Calories quotidiennes : {profile['target_calories']} kcal
        - Protéines : {profile['macros']['proteins']}g
        - Glucides : {profile['macros']['carbs']}g
        - Lipides : {profile['macros']['fats']}g
        - Régime : {', '.join(preferences['diet_type'])}
        - Allergies : {preferences['allergies']}
        - Budget : {preferences['budget']}
        - Temps de préparation max : {preferences['prep_time']}
        
        Génère un plan sur {preferences['variety']} jours avec {preferences['meals_per_day']} repas par jour.
        Pour chaque repas, inclure :
        1. Nom du repas
        2. Liste des ingrédients avec quantités
        3. Valeurs nutritionnelles (calories, protéines, glucides, lipides)
        4. Instructions de préparation simples
        
        Réponds en format JSON structuré."""
        
        # Appeler l'API OpenAI
        completion = openai_client.chat.completions.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": "Tu es un expert en nutrition qui génère des plans alimentaires personnalisés."},
                {"role": "user", "content": prompt}
            ],
            max_tokens=2000,
            temperature=0.7
        )
        
        # Récupérer et parser la réponse
        try:
            response = completion.choices[0].message.content
            meal_plan = json.loads(response)
            return meal_plan
        except json.JSONDecodeError:
            # Si la réponse n'est pas un JSON valide, retourner la réponse brute
            return {"raw_response": response}
        
    except Exception as e:
        st.error(f"Erreur lors de la génération du plan : {str(e)}")
        return {}

# Configuration de la page
st.set_page_config(
    page_title="FitLife - Assistant Nutritionnel",
    page_icon="🥗",
    layout="wide",
    initial_sidebar_state="expanded"
)

# CSS personnalisé
st.markdown("""
<style>
    .main-header {
        font-size: 2.5rem;
        font-weight: bold;
        color: #FF6B35;
        text-align: center;
        margin-bottom: 2rem;
    }
    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1.5rem;
        border-radius: 10px;
        color: white;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .stButton>button {
        background-color: #FF6B35;
        color: white;
        border-radius: 20px;
        padding: 0.5rem 2rem;
        font-weight: bold;
        border: none;
        transition: all 0.3s;
    }
    .stButton>button:hover {
        background-color: #E55A2B;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .info-box {
        background-color: #f0f2f6;
        padding: 1rem;
        border-radius: 10px;
        border-left: 4px solid #FF6B35;
        margin: 1rem 0;
    }
</style>
""", unsafe_allow_html=True)

# Initialisation de la session
if 'profile' not in st.session_state:
    st.session_state.profile = None
if 'nutritional_needs' not in st.session_state:
    st.session_state.nutritional_needs = None
if 'weight_history' not in st.session_state:
    st.session_state.weight_history = []
if 'meal_plan' not in st.session_state:
    st.session_state.meal_plan = None
if 'chat_history' not in st.session_state:
    st.session_state.chat_history = []

# Classe pour les calculs nutritionnels
class NutritionalCalculator:
    @staticmethod
    def calculate_bmr(weight, height, age, sex):
        """Calcul du métabolisme de base (Mifflin-St Jeor)"""
        if sex == 'Homme':
            bmr = (10 * weight) + (6.25 * height) - (5 * age) + 5
        else:
            bmr = (10 * weight) + (6.25 * height) - (5 * age) - 161
        return round(bmr, 2)
    
    @staticmethod
    def calculate_tdee(bmr, activity_level):
        """Calcul de la dépense énergétique totale"""
        activity_factors = {
            'Sédentaire': 1.2,
            'Légèrement actif': 1.375,
            'Modérément actif': 1.55,
            'Très actif': 1.725,
            'Extrêmement actif': 1.9
        }
        return round(bmr * activity_factors[activity_level], 2)
    
    @staticmethod
    def calculate_target_calories(tdee, goal):
        """Ajustement selon l'objectif"""
        adjustments = {
            'Perte de poids': 0.85,
            'Maintien': 1.0,
            'Prise de masse': 1.15
        }
        return round(tdee * adjustments[goal], 2)
    
    @staticmethod
    def calculate_macros(calories, weight, goal):
        """Répartition des macronutriments"""
        if goal == 'Prise de masse':
            proteins = weight * 2.0
        else:
            proteins = weight * 1.8
        
        proteins_cal = proteins * 4
        fats_cal = calories * 0.25
        fats = fats_cal / 9
        carbs_cal = calories - proteins_cal - fats_cal
        carbs = carbs_cal / 4
        
        return {
            'proteins': round(proteins, 1),
            'carbs': round(carbs, 1),
            'fats': round(fats, 1),
            'proteins_cal': round(proteins_cal, 1),
            'carbs_cal': round(carbs_cal, 1),
            'fats_cal': round(fats_cal, 1)
        }
    
    @staticmethod
    def estimate_duration(current_weight, target_weight, goal):
        """Estimation de la durée pour atteindre l'objectif"""
        weight_diff = abs(current_weight - target_weight)
        
        if goal == 'Perte de poids':
            # Perte saine: 0.5-1 kg/semaine
            weeks = weight_diff / 0.75
        elif goal == 'Prise de masse':
            # Gain sain: 0.25-0.5 kg/semaine
            weeks = weight_diff / 0.375
        else:
            return 0
        
        return round(weeks, 1)

# Fonction pour charger les données alimentaires
@st.cache_data
def load_food_data():
    """Charge le dataset alimentaire depuis les fichiers CSV"""
    try:
        # Charger et combiner les données des différents groupes
        dfs = []
        for i in range(1, 6):
            file_path = f"data-nutrition/FINAL FOOD DATASET/FOOD-DATA-GROUP{i}.csv"
            df = pd.read_csv(file_path)
            dfs.append(df)
        
        # Combiner tous les dataframes
        combined_df = pd.concat(dfs, ignore_index=True)
        
        # Charger les métadonnées
        metadata = pd.read_csv("data-nutrition/FINAL FOOD DATASET/METADATA/Combined_FOOD_METADATA.csv")
        
        # Joindre les métadonnées si nécessaire
        if 'food_id' in combined_df.columns and 'food_id' in metadata.columns:
            final_df = pd.merge(combined_df, metadata, on='food_id', how='left')
        else:
            final_df = combined_df
            
        return final_df
        
    except Exception as e:
        st.error(f"Erreur lors du chargement des données : {str(e)}")
        # Retourner un DataFrame vide ou exemple en cas d'erreur
        return pd.DataFrame({
            'food': ['Poulet grillé', 'Riz complet', 'Brocoli'],
            'Caloric Value': [165, 370, 34],
            'Protein': [31, 7.9, 2.8],
            'Carbohydrates': [0, 77, 6.6],
            'Fat': [3.6, 2.9, 0.4],
            'Dietary Fiber': [0, 3.5, 2.6]
        })

# Sidebar - Navigation
st.sidebar.markdown("# 🥗 FitLife Nutrition")
st.sidebar.markdown("---")

page = st.sidebar.radio(
    "Navigation",
    ["🏠 Accueil", "👤 Profil Nutritionnel", "📊 Tableau de Bord", 
     "🍽️ Générateur de Plan", "💬 Assistant IA", "📈 Suivi Poids", "📚 Base Alimentaire"]
)

st.sidebar.markdown("---")
if st.session_state.profile:
    st.sidebar.success("✅ Profil configuré")
    st.sidebar.info(f"**Objectif:** {st.session_state.profile['goal']}")
else:
    st.sidebar.warning("⚠️ Configurez votre profil")

# PAGE 1: ACCUEIL
if page == "🏠 Accueil":
    st.markdown('<h1 class="main-header">🥗 Assistant Intelligent Nutritionnel FitLife</h1>', unsafe_allow_html=True)
    
    col1, col2, col3 = st.columns(3)
    
    with col1:
        st.markdown("""
        <div class="metric-card">
            <h2>📊</h2>
            <h3>Calcul Précis</h3>
            <p>Besoins nutritionnels basés sur des formules scientifiques validées</p>
        </div>
        """, unsafe_allow_html=True)
    
    with col2:
        st.markdown("""
        <div class="metric-card">
            <h2>🤖</h2>
            <h3>IA Avancée</h3>
            <p>Plans alimentaires personnalisés générés par Claude AI</p>
        </div>
        """, unsafe_allow_html=True)
    
    with col3:
        st.markdown("""
        <div class="metric-card">
            <h2>📈</h2>
            <h3>Suivi Progression</h3>
            <p>Visualisez votre évolution vers vos objectifs</p>
        </div>
        """, unsafe_allow_html=True)
    
    st.markdown("---")
    
    st.markdown("""
    ## 🎯 Bienvenue dans votre Assistant Nutritionnel
    
    Cette plateforme vous accompagne dans l'atteinte de vos objectifs en combinant :
    - **Science** : Formules de calcul validées (Mifflin-St Jeor, Harris-Benedict)
    - **Intelligence Artificielle** : Plans alimentaires personnalisés
    - **Suivi** : Graphiques de progression en temps réel
    
    ### 🚀 Commencez en 3 étapes :
    1. **Configurez votre profil** : Renseignez vos informations (poids, taille, objectif)
    2. **Obtenez vos besoins** : Calcul automatique de vos besoins caloriques et macronutriments
    3. **Générez votre plan** : L'IA crée un plan alimentaire adapté à vos besoins
    
    ### 💡 Fonctionnalités disponibles :
    - ✅ Calcul des besoins nutritionnels personnalisés
    - ✅ Génération de plans alimentaires via IA
    - ✅ Assistant conversationnel pour vos questions
    - ✅ Suivi de poids avec graphiques interactifs
    - ✅ Base de données alimentaire complète
    - ✅ Export PDF de vos plans
    """)
    
    if not st.session_state.profile:
        st.warning("⚠️ Commencez par configurer votre profil nutritionnel dans la section '👤 Profil Nutritionnel'")
        if st.button("🚀 Configurer mon profil maintenant", use_container_width=True):
            st.rerun()

# PAGE 2: PROFIL NUTRITIONNEL
elif page == "👤 Profil Nutritionnel":
    st.markdown('<h1 class="main-header">👤 Configuration du Profil Nutritionnel</h1>', unsafe_allow_html=True)
    
    with st.form("profile_form"):
        st.markdown("### 📝 Informations personnelles")
        
        col1, col2 = st.columns(2)
        
        with col1:
            weight = st.number_input("Poids actuel (kg)", min_value=30.0, max_value=200.0, value=70.0, step=0.1)
            height = st.number_input("Taille (cm)", min_value=120, max_value=220, value=170, step=1)
            age = st.number_input("Âge", min_value=15, max_value=100, value=25, step=1)
        
        with col2:
            sex = st.selectbox("Sexe", ["Homme", "Femme"])
            target_weight = st.number_input("Poids cible (kg)", min_value=30.0, max_value=200.0, value=65.0, step=0.1)
            goal = st.selectbox("Objectif", ["Perte de poids", "Maintien", "Prise de masse"])
        
        st.markdown("### 🏃 Niveau d'activité")
        activity_level = st.select_slider(
            "Sélectionnez votre niveau d'activité physique",
            options=['Sédentaire', 'Légèrement actif', 'Modérément actif', 'Très actif', 'Extrêmement actif'],
            value='Modérément actif'
        )
        
        st.markdown("### 🍽️ Préférences alimentaires")
        col1, col2 = st.columns(2)
        
        with col1:
            diet_type = st.multiselect(
                "Régime alimentaire",
                ["Omnivore", "Végétarien", "Végétalien", "Sans gluten", "Sans lactose"],
                default=["Omnivore"]
            )
        
        with col2:
            allergies = st.text_area("Allergies ou intolérances", placeholder="Ex: Arachides, fruits de mer...")
        
        submitted = st.form_submit_button("💾 Enregistrer le profil", use_container_width=True)
        
        if submitted:
            # Calculs
            calc = NutritionalCalculator()
            bmr = calc.calculate_bmr(weight, height, age, sex)
            tdee = calc.calculate_tdee(bmr, activity_level)
            target_calories = calc.calculate_target_calories(tdee, goal)
            macros = calc.calculate_macros(target_calories, weight, goal)
            duration = calc.estimate_duration(weight, target_weight, goal)
            
            # Sauvegarde du profil
            st.session_state.profile = {
                'weight': weight,
                'height': height,
                'age': age,
                'sex': sex,
                'target_weight': target_weight,
                'goal': goal,
                'activity_level': activity_level,
                'diet_type': diet_type,
                'allergies': allergies,
                'created_at': datetime.now()
            }
            
            # Sauvegarde des besoins
            st.session_state.nutritional_needs = {
                'bmr': bmr,
                'tdee': tdee,
                'target_calories': target_calories,
                'macros': macros,
                'duration': duration
            }
            
            st.success("✅ Profil enregistré avec succès!")
            st.balloons()
            st.rerun()

# PAGE 3: TABLEAU DE BORD
elif page == "📊 Tableau de Bord":
    st.markdown('<h1 class="main-header">📊 Tableau de Bord Nutritionnel</h1>', unsafe_allow_html=True)
    
    if not st.session_state.profile:
        st.warning("⚠️ Veuillez d'abord configurer votre profil nutritionnel")
    else:
        profile = st.session_state.profile
        needs = st.session_state.nutritional_needs
        
        # Métriques principales
        col1, col2, col3, col4 = st.columns(4)
        
        with col1:
            st.metric("🔥 Calories/jour", f"{needs['target_calories']:.0f} kcal")
        with col2:
            st.metric("🥩 Protéines", f"{needs['macros']['proteins']:.0f}g")
        with col3:
            st.metric("🍚 Glucides", f"{needs['macros']['carbs']:.0f}g")
        with col4:
            st.metric("🥑 Lipides", f"{needs['macros']['fats']:.0f}g")
        
        st.markdown("---")
        
        # Graphiques
        col1, col2 = st.columns(2)
        
        with col1:
            st.markdown("### 📊 Répartition des Macronutriments")
            
            fig = go.Figure(data=[go.Pie(
                labels=['Protéines', 'Glucides', 'Lipides'],
                values=[
                    needs['macros']['proteins_cal'],
                    needs['macros']['carbs_cal'],
                    needs['macros']['fats_cal']
                ],
                hole=.3,
                marker_colors=['#FF6B6B', '#4ECDC4', '#FFE66D']
            )])
            
            fig.update_layout(
                height=400,
                showlegend=True,
                legend=dict(orientation="h", yanchor="bottom", y=-0.2)
            )
            
            st.plotly_chart(fig, use_container_width=True)
        
        with col2:
            st.markdown("### 📈 Objectif de Poids")
            
            weight_diff = abs(profile['weight'] - profile['target_weight'])
            progress = 0 if weight_diff == 0 else (1 - weight_diff / abs(profile['weight'] - profile['target_weight'])) * 100
            
            fig = go.Figure(go.Indicator(
                mode="gauge+number+delta",
                value=profile['weight'],
                domain={'x': [0, 1], 'y': [0, 1]},
                title={'text': "Poids Actuel (kg)"},
                delta={'reference': profile['target_weight']},
                gauge={
                    'axis': {'range': [None, max(profile['weight'], profile['target_weight']) + 10]},
                    'bar': {'color': "#FF6B35"},
                    'steps': [
                        {'range': [0, profile['target_weight']], 'color': "lightgray"}
                    ],
                    'threshold': {
                        'line': {'color': "red", 'width': 4},
                        'thickness': 0.75,
                        'value': profile['target_weight']
                    }
                }
            ))
            
            fig.update_layout(height=400)
            st.plotly_chart(fig, use_container_width=True)
        
        # Informations détaillées
        st.markdown("---")
        st.markdown("### 📋 Détails de vos besoins nutritionnels")
        
        col1, col2 = st.columns(2)
        
        with col1:
            st.markdown(f"""
            <div class="info-box">
            <h4>🔬 Calculs Scientifiques</h4>
            <ul>
                <li><strong>Métabolisme de base (BMR):</strong> {needs['bmr']:.0f} kcal/jour</li>
                <li><strong>Dépense énergétique totale (TDEE):</strong> {needs['tdee']:.0f} kcal/jour</li>
                <li><strong>Calories cibles:</strong> {needs['target_calories']:.0f} kcal/jour</li>
                <li><strong>Durée estimée:</strong> {needs['duration']:.1f} semaines</li>
            </ul>
            </div>
            """, unsafe_allow_html=True)
        
        with col2:
            st.markdown(f"""
            <div class="info-box">
            <h4>🎯 Votre Profil</h4>
            <ul>
                <li><strong>Objectif:</strong> {profile['goal']}</li>
                <li><strong>Niveau d'activité:</strong> {profile['activity_level']}</li>
                <li><strong>Poids actuel:</strong> {profile['weight']:.1f} kg</li>
                <li><strong>Poids cible:</strong> {profile['target_weight']:.1f} kg</li>
                <li><strong>Différence:</strong> {abs(profile['weight'] - profile['target_weight']):.1f} kg</li>
            </ul>
            </div>
            """, unsafe_allow_html=True)

# PAGE 4: GÉNÉRATEUR DE PLAN
elif page == "🍽️ Générateur de Plan":
    st.markdown('<h1 class="main-header">🍽️ Générateur de Plan Alimentaire</h1>', unsafe_allow_html=True)
    
    if not st.session_state.profile:
        st.warning("⚠️ Veuillez d'abord configurer votre profil nutritionnel")
    else:
        st.markdown("""
        ### 🤖 Génération de Plan Alimentaire par IA
        
        L'assistant IA va créer un plan alimentaire personnalisé basé sur :
        - Vos besoins caloriques et macronutriments
        - Votre objectif (perte/maintien/prise de masse)
        - Vos préférences et restrictions alimentaires
        """)
        
        with st.form("meal_plan_form"):
            col1, col2 = st.columns(2)
            
            with col1:
                meals_per_day = st.slider("Nombre de repas par jour", 3, 6, 4)
                budget = st.selectbox("Budget", ["Économique", "Moyen", "Élevé"])
            
            with col2:
                prep_time = st.selectbox("Temps de préparation", ["Rapide (<30min)", "Moyen (30-60min)", "Élaboré (>60min)"])
                variety = st.slider("Variété (nombre de jours différents)", 1, 7, 7)
            
            generate = st.form_submit_button("🎨 Générer mon plan alimentaire", use_container_width=True)
            
            if generate:
                with st.spinner("🤖 L'IA génère votre plan personnalisé..."):
                    # Simulation de génération (dans la version finale, appel à Claude API)
                    import time
                    time.sleep(2)
                    
                    # Plan exemple
                    sample_plan = {
                        "Lundi": {
                            "Petit-déjeuner": {
                                "aliments": ["Flocons d'avoine (80g)", "Banane (1)", "Amandes (30g)", "Miel (1 c.à.s)"],
                                "calories": 520,
                                "proteines": 15,
                                "glucides": 72,
                                "lipides": 18
                            },
                            "Déjeuner": {
                                "aliments": ["Poulet grillé (150g)", "Riz complet (100g)", "Brocoli vapeur (200g)", "Huile d'olive (1 c.à.s)"],
                                "calories": 580,
                                "proteines": 52,
                                "glucides": 58,
                                "lipides": 15
                            },
                            "Collation": {
                                "aliments": ["Yaourt grec (200g)", "Myrtilles (100g)"],
                                "calories": 180,
                                "proteines": 20,
                                "glucides": 22,
                                "lipides": 2
                            },
                            "Dîner": {
                                "aliments": ["Saumon grillé (120g)", "Patate douce (150g)", "Épinards (150g)", "Avocat (½)"],
                                "calories": 520,
                                "proteines": 38,
                                "glucides": 45,
                                "lipides": 22
                            }
                        }
                    }
                    
                    st.session_state.meal_plan = sample_plan
                    st.success("✅ Plan alimentaire généré avec succès!")
                    st.balloons()
        
        # Affichage du plan
        if st.session_state.meal_plan:
            st.markdown("---")
            st.markdown("### 📅 Votre Plan Alimentaire de la Semaine")
            
            for day, meals in st.session_state.meal_plan.items():
                with st.expander(f"📆 {day}", expanded=True):
                    cols = st.columns(len(meals))
                    
                    for idx, (meal_name, meal_data) in enumerate(meals.items()):
                        with cols[idx]:
                            st.markdown(f"**{meal_name}**")
                            st.markdown("**Aliments:**")
                            for aliment in meal_data['aliments']:
                                st.markdown(f"- {aliment}")
                            
                            st.markdown(f"""
                            **Valeurs nutritionnelles:**
                            - 🔥 {meal_data['calories']} kcal
                            - 🥩 {meal_data['proteines']}g protéines
                            - 🍚 {meal_data['glucides']}g glucides
                            - 🥑 {meal_data['lipides']}g lipides
                            """)
            
            col1, col2, col3 = st.columns(3)
            with col1:
                if st.button("🔄 Régénérer le plan", use_container_width=True):
                    st.session_state.meal_plan = None
                    st.rerun()
            with col2:
                if st.button("📄 Exporter en PDF", use_container_width=True):
                    st.info("📄 Fonctionnalité d'export PDF à venir")
            with col3:
                if st.button("💾 Sauvegarder", use_container_width=True):
                    st.success("✅ Plan sauvegardé!")

# PAGE 5: ASSISTANT IA
elif page == "💬 Assistant IA":
    st.markdown('<h1 class="main-header">💬 Assistant IA - Posez vos Questions</h1>', unsafe_allow_html=True)
    
    st.markdown("""
    ### 🤖 Assistant Nutritionnel Intelligent
    
    Posez toutes vos questions sur la nutrition, les aliments, les recettes, etc.
    L'assistant utilise Claude AI pour vous fournir des réponses personnalisées.
    """)
    
    # Quick replies
    st.markdown("**💡 Questions suggérées:**")
    col1, col2, col3 = st.columns(3)
    
    with col1:
        if st.button("🍳 Idées petit-déjeuner protéiné"):
            st.session_state.chat_history.append({
                "role": "user",
                "content": "Peux-tu me suggérer des idées de petit-déjeuner riche en protéines ?"
            })
    
    with col2:
        if st.button("🏋️ Nutrition post-entraînement"):
            st.session_state.chat_history.append({
                "role": "user",
                "content": "Que dois-je manger après mon entraînement ?"
            })
    
    with col3:
        if st.button("🥗 Recettes rapides"):
            st.session_state.chat_history.append({
                "role": "user",
                "content": "Quelles sont des recettes saines et rapides à préparer ?"
            })
    
    st.markdown("---")
    
    # Zone de chat
    chat_container = st.container()
    
    with chat_container:
        for message in st.session_state.chat_history:
            if message["role"] == "user":
                st.markdown(f"""
                <div style='background-color: #E3F2FD; padding: 1rem; border-radius: 10px; margin: 0.5rem 0; margin-left: 20%;'>
                    <strong>Vous:</strong> {message["content"]}
                </div>
                """, unsafe_allow_html=True)
            else:
                st.markdown(f"""
                <div style='background-color: #F5F5F5; padding: 1rem; border-radius: 10px; margin: 0.5rem 0; margin-right: 20%;'>
                    <strong>🤖 Assistant:</strong> {message["content"]}
                </div>
                """, unsafe_allow_html=True)
    
    # Input utilisateur
    user_question = st.text_input("💬 Posez votre question...", key="user_input")
    
    col1, col2 = st.columns([6, 1])
    with col2:
        send_button = st.button("📤 Envoyer", use_container_width=True)
    
    if send_button and user_question:
        # Ajouter la question
        st.session_state.chat_history.append({
            "role": "user",
            "content": user_question
        })
        
        # Simulation de réponse (dans la version finale, appel à Claude API)
        with st.spinner("🤖 L'assistant réfléchit..."):
            import time
            time.sleep(1)
            
            response = f"Merci pour votre question sur '{user_question}'. Pour vous donner une réponse personnalisée, je prendrais en compte vos besoins nutritionnels de {st.session_state.nutritional_needs['target_calories']:.0f} kcal/jour et votre objectif de {st.session_state.profile['goal']}. [Réponse détaillée à venir avec l'intégration Claude API]"
            
            st.session_state.chat_history.append({
                "role": "assistant",
                "content": response
            })
        
        st.rerun()
    
    # Bouton pour effacer l'historique
    if st.session_state.chat_history:
        if st.button("🗑️ Effacer l'historique"):
            st.session_state.chat_history = []
            st.rerun()

# PAGE 6: SUIVI POIDS
elif page == "📈 Suivi Poids":
    st.markdown('<h1 class="main-header">📈 Suivi de Poids</h1>', unsafe_allow_html=True)
    
    if not st.session_state.profile:
        st.warning("⚠️ Veuillez d'abord configurer votre profil nutritionnel")
    else:
        col1, col2 = st.columns([2, 1])
        
        with col1:
            st.markdown("### 📊 Enregistrer une nouvelle pesée")
            
            with st.form("weight_entry"):
                col_a, col_b = st.columns(2)
                
                with col_a:
                    weight_date = st.date_input("Date de pesée", value=datetime.now())
                    weight_value = st.number_input("Poids (kg)", min_value=30.0, max_value=200.0, value=st.session_state.profile['weight'], step=0.1)
                
                with col_b:
                    notes = st.text_area("Notes (optionnel)", placeholder="Ex: Ressenti, observations...")
                
                submit_weight = st.form_submit_button("💾 Enregistrer", use_container_width=True)
                
                if submit_weight:
                    st.session_state.weight_history.append({
                        'date': weight_date,
                        'weight': weight_value,
                        'notes': notes
                    })
                    st.success(f"✅ Poids enregistré: {weight_value} kg le {weight_date}")
                    st.balloons()
        
        with col2:
            if st.session_state.weight_history:
                latest_weight = st.session_state.weight_history[-1]['weight']
                initial_weight = st.session_state.profile['weight']
                target_weight = st.session_state.profile['target_weight']
                
                progress = abs(initial_weight - latest_weight)
                total_to_go = abs(initial_weight - target_weight)
                progress_pct = (progress / total_to_go * 100) if total_to_go > 0 else 0
                
                st.markdown("### 🎯 Progression")
                st.metric(
                    "Dernier poids",
                    f"{latest_weight:.1f} kg",
                    f"{latest_weight - initial_weight:+.1f} kg"
                )
                st.progress(min(progress_pct / 100, 1.0))
                st.markdown(f"**{progress_pct:.1f}%** de l'objectif atteint")
        
        # Graphique d'évolution
        if st.session_state.weight_history:
            st.markdown("---")
            st.markdown("### 📈 Évolution du Poids")
            
            # Préparer les données
            dates = [entry['date'] for entry in st.session_state.weight_history]
            weights = [entry['weight'] for entry in st.session_state.weight_history]
            
            # Ajouter le poids initial si pas encore de pesée à cette date
            if not any(entry['date'] == st.session_state.profile['created_at'].date() for entry in st.session_state.weight_history):
                dates.insert(0, st.session_state.profile['created_at'].date())
                weights.insert(0, st.session_state.profile['weight'])
            
            # Créer le graphique
            fig = go.Figure()
            
            # Ligne de poids
            fig.add_trace(go.Scatter(
                x=dates,
                y=weights,
                mode='lines+markers',
                name='Poids actuel',
                line=dict(color='#FF6B35', width=3),
                marker=dict(size=10)
            ))
            
            # Ligne objectif
            fig.add_trace(go.Scatter(
                x=[dates[0], dates[-1] + timedelta(days=30)],
                y=[st.session_state.profile['target_weight'], st.session_state.profile['target_weight']],
                mode='lines',
                name='Objectif',
                line=dict(color='green', width=2, dash='dash')
            ))
            
            # Ligne de tendance
            if len(weights) >= 2:
                z = np.polyfit(range(len(weights)), weights, 1)
                p = np.poly1d(z)
                trend_y = [p(i) for i in range(len(weights))]
                
                fig.add_trace(go.Scatter(
                    x=dates,
                    y=trend_y,
                    mode='lines',
                    name='Tendance',
                    line=dict(color='blue', width=2, dash='dot')
                ))
            
            fig.update_layout(
                title="Évolution de votre poids",
                xaxis_title="Date",
                yaxis_title="Poids (kg)",
                height=500,
                hovermode='x unified',
                showlegend=True
            )
            
            st.plotly_chart(fig, use_container_width=True)
            
            # Statistiques
            st.markdown("---")
            col1, col2, col3, col4 = st.columns(4)
            
            with col1:
                st.metric("📊 Poids initial", f"{st.session_state.profile['weight']:.1f} kg")
            with col2:
                st.metric("📍 Poids actuel", f"{weights[-1]:.1f} kg")
            with col3:
                st.metric("🎯 Objectif", f"{st.session_state.profile['target_weight']:.1f} kg")
            with col4:
                remaining = abs(weights[-1] - st.session_state.profile['target_weight'])
                st.metric("⏳ Reste", f"{remaining:.1f} kg")
            
            # Historique détaillé
            st.markdown("---")
            st.markdown("### 📋 Historique des Pesées")
            
            history_df = pd.DataFrame(st.session_state.weight_history)
            history_df = history_df.sort_values('date', ascending=False)
            
            st.dataframe(
                history_df[['date', 'weight', 'notes']],
                column_config={
                    'date': st.column_config.DateColumn('Date', format="DD/MM/YYYY"),
                    'weight': st.column_config.NumberColumn('Poids (kg)', format="%.1f kg"),
                    'notes': 'Notes'
                },
                hide_index=True,
                use_container_width=True
            )

# PAGE 7: BASE ALIMENTAIRE
elif page == "📚 Base Alimentaire":
    st.markdown('<h1 class="main-header">📚 Base de Données Alimentaire</h1>', unsafe_allow_html=True)
    
    # Charger les données
    food_data = load_food_data()
    
    st.markdown("""
    ### 🔍 Explorez notre base de données alimentaire
    Recherchez des aliments et consultez leurs valeurs nutritionnelles détaillées.
    """)
    
    # Filtres
    col1, col2, col3 = st.columns(3)
    
    with col1:
        search_term = st.text_input("🔍 Rechercher un aliment", placeholder="Ex: Poulet, riz...")
    
    with col2:
        sort_by = st.selectbox("Trier par", ["Nom", "Calories", "Protéines", "Glucides", "Lipides"])
    
    with col3:
        min_protein = st.slider("Protéines min (g)", 0, 50, 0)
    
    # Filtrer les données
    filtered_data = food_data.copy()
    
    if search_term:
        filtered_data = filtered_data[filtered_data['food'].str.contains(search_term, case=False, na=False)]
    
    filtered_data = filtered_data[filtered_data['Protein'] >= min_protein]
    
    # Trier
    sort_mapping = {
        "Nom": "food",
        "Calories": "Caloric Value",
        "Protéines": "Protein",
        "Glucides": "Carbohydrates",
        "Lipides": "Fat"
    }
    
    filtered_data = filtered_data.sort_values(sort_mapping[sort_by], ascending=False)
    
    st.markdown(f"### 📊 Résultats ({len(filtered_data)} aliments)")
    
    # Afficher les aliments sous forme de cards
    for idx, row in filtered_data.iterrows():
        with st.expander(f"🍽️ {row['food']} - {row['Caloric Value']} kcal/100g"):
            col1, col2 = st.columns(2)
            
            with col1:
                st.markdown("#### 📊 Macronutriments (pour 100g)")
                st.markdown(f"""
                - **🔥 Calories:** {row['Caloric Value']} kcal
                - **🥩 Protéines:** {row['Protein']}g
                - **🍚 Glucides:** {row['Carbohydrates']}g
                - **🥑 Lipides:** {row['Fat']}g
                - **🌾 Fibres:** {row['Dietary Fiber']}g
                """)
            
            with col2:
                # Graphique en barres des macros
                fig = go.Figure(data=[
                    go.Bar(
                        x=['Protéines', 'Glucides', 'Lipides'],
                        y=[row['Protein'], row['Carbohydrates'], row['Fat']],
                        marker_color=['#FF6B6B', '#4ECDC4', '#FFE66D']
                    )
                ])
                
                fig.update_layout(
                    title="Répartition des macronutriments",
                    yaxis_title="Grammes",
                    height=300,
                    showlegend=False
                )
                
                st.plotly_chart(fig, use_container_width=True)
            
            # Boutons d'action
            col_a, col_b, col_c = st.columns(3)
            
            with col_a:
                if st.button(f"➕ Ajouter au plan", key=f"add_{idx}"):
                    st.success(f"✅ {row['food']} ajouté!")
            
            with col_b:
                if st.button(f"⭐ Favori", key=f"fav_{idx}"):
                    st.info(f"⭐ {row['food']} ajouté aux favoris!")
            
            with col_c:
                if st.button(f"📊 Détails", key=f"det_{idx}"):
                    st.info("Détails complets à venir!")
    
    # Statistiques globales
    if len(filtered_data) > 0:
        st.markdown("---")
        st.markdown("### 📈 Statistiques de la sélection")
        
        col1, col2, col3, col4 = st.columns(4)
        
        with col1:
            st.metric("Calories moyennes", f"{filtered_data['Caloric Value'].mean():.0f} kcal")
        with col2:
            st.metric("Protéines moyennes", f"{filtered_data['Protein'].mean():.1f}g")
        with col3:
            st.metric("Glucides moyens", f"{filtered_data['Carbohydrates'].mean():.1f}g")
        with col4:
            st.metric("Lipides moyens", f"{filtered_data['Fat'].mean():.1f}g")

# Footer
st.markdown("---")
st.markdown("""
<div style='text-align: center; color: #666; padding: 2rem;'>
    <p><strong>🥗 FitLife - Assistant Intelligent Nutritionnel</strong></p>
    <p>Développé avec ❤️ par Asma Bélkahla & Monia Selleoui| Powered by Claude AI & Streamlit</p>
    <p style='font-size: 0.8rem;'>⚠️ Les conseils fournis sont à titre informatif. Consultez un professionnel de santé pour un suivi personnalisé.</p>
</div>
""", unsafe_allow_html=True)