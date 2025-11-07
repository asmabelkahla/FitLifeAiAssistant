<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionType;

class SubscriptionController extends Controller
{
    public function index()
    {
      
       $subscriptions = Auth::user()->subscriptions()->with('subscriptionType')->get();;
 
        return view('subscription', compact('subscriptions'));
    }

    public function create()
    {
    $subscriptionTypes = SubscriptionType::all();
        return view('addsubscription', compact('subscriptionTypes'));
    }

    public function store(Request $request)
    {//dd($request);
        $validatedData= $request->validate([
          
            'subscription_type_id' =>  'required|exists:subscription_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        
        ]);
        $user = Auth::user();
        $user->subscriptions()->create($validatedData + ['status' => 'active']);
      
    

      

        return redirect()->route('subscriptions')->with('success', 'Subscription created successfully.');
    }

  
    public function cancel($id)
    {
        // Récupérer l'abonnement à annuler
        $subscription = Subscription::findOrFail($id);

        // Vérifie si l'utilisateur connecté est bien celui qui possède l'abonnement
        if ($subscription->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Met à jour le statut de l'abonnement à 'canceled'
        $subscription->status = 'cancelled';
        $subscription->update();

        return redirect()->route('subscriptions')->with('success', 'Subscription canceled successfully.');
    }

    public function edit(Subscription $subscription)
    {
        $subscriptionTypes = SubscriptionType::all();
        return view('editsubscription', compact('subscription','subscriptionTypes'));
    }

    public function update(Request $request, Subscription $subscription)
    {//dd($request);
        $request->validate([
            'subscription_type_id' => 'required|exists:subscription_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
     
        ]);

        $subscription->update($request->all());

        return redirect()->route('subscriptions')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('subscriptions')->with('success', 'Subscription deleted successfully.');
    }
}
