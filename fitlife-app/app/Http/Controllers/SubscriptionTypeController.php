<?php

namespace App\Http\Controllers;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class SubscriptionTypeController extends Controller
{
    //

    public function index()
    {
        $subscriptionTypes = SubscriptionType::all();
        return view('subscriptionType', compact('subscriptionTypes'));
    }



    public function create()
    {
        return view('addtype');
    }


    public function store(Request $request)
    { //dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        SubscriptionType::create($request->all());

        return redirect()->route('types')->with('success', 'Subscription Type created successfully.');
    }

    public function edit(SubscriptionType $subscriptionType)
    {
        return view('editsubscriptiontypes', compact('subscriptionType'));
    }

    public function update(Request $request, SubscriptionType $subscriptionType)
    {dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $subscriptionType->update($request->all());

        return redirect()->route('types')->with('success', 'Subscription Type updated successfully.');
    }

    public function destroy(SubscriptionType $subscriptionType)
    {
        $subscriptionType->delete();

        return redirect()->route('types')->with('success', 'Subscription Type deleted successfully.');
    }
}
