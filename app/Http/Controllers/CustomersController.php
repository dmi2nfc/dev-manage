<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Customer::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $customer = Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email', null),
            'notes' => $request->input('notes', null),
            'is_active' => $request->input('is_active', true),
        ]);

        return $customer;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get customer
        $customer = Customer::findOrFail($id);

        return $customer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $customer = Customer::findOrFail($request->customer_id);
                    
        $this->validate($request, [
            'name' => 'required'
        ]);

        $customer->name = $request->input('name');
        $customer->email = $request->input('email', null);
        $customer->notes = $request->input('notes', null);
        $customer->is_active = $request->input('is_active', true);

        if($customer->save()){
            return $customer;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get customer
        $customer = Customer::findOrFail($id);

        if($customer->delete()){
            return $customer;
        }
    }
}
