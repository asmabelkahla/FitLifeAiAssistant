<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
class AdminController extends Controller
{ public function index()
    {
        // Code pour afficher la page d'accueil du tableau de bord
        return view('admin.dashboard');
    }
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Gate::denies('access-dashboard')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }
}
