<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use Illuminate\Http\Request;

class WorkersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::select(['id', 'name'])
            ->with('activeSprints', 'activeSprints.project', 'activeSprints.project.owner', 'activeSprints.currency')
            ->get();
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
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $worker = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email', null),
            'password' => bcrypt($request->input('password')),
            'api_token' => bcrypt($request->input('password')),
        ]);

        return User::select(['id', 'name'])
                    ->with('activeSprints', 'activeSprints.project', 'activeSprints.project.owner')
                    ->where('id', $worker->id)
                    ->get()
                    ->first();

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

        $worker = User::findOrFail($request->worker_id);

        $this->validate($request, [
            'name' => 'required'
        ]);

        $worker->name = $request->input('name');
        $worker->email = $request->input('email', null);
        $worker->password = $request->input('password', null);

        if($worker->save()){
            return $worker;
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
        $worker = User::findOrFail($id);

        if($worker->delete()){
            return $worker;
        }
    }
}
