<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function Home()
    {
        return view('dashboard');
    }
    public function About()
    {
        return view('about');
    }
    public function Trainer()
    {
        return view('trainer');
    }
    public function classes()
    {
        return view('classes');
    }
    public function schedule()
    {
        return view('schedule');
    }
    public function contact()
    {
        return view('contact');
    }
}
