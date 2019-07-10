<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use App\Customer;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Payment::with('customer')->get();
        //return Payment::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'customer' => 'required',
            'amount' => 'required',
        ]);

        $input = $request->all();

        $dateFrom = $input['date_from']
                ? \Carbon\Carbon::parse($input['date_from'])->tz('UTC'):
                \Carbon\Carbon::now()->tz('UTC');
        $dateTo = $input['date_to']
                ? \Carbon\Carbon::parse($input['date_to'])->tz('UTC'):
                \Carbon\Carbon::now()->tz('UTC');

        $transaction = Customer::find($input['customer'])->payments()->create([
            'amount' => $request->input('amount'),
            'profit' => $request->input('amount'),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'description' => $request->input('description', null),
        ]);

        return $transaction;
        // $project = Customer::find($input['customer'])->projects()->create([
        //     // 'user_id' => $request->input('user_id'),
        //     // 'project_id' => $request->input('project'),
        //     'name' => $request->input('name'),
        //     'rate' => $request->input('rate'),
        //     'currency' => $request->input('currency', '$'),
        //     'rate_type' => $request->input('rate_type', 'hourly'),
        //     // 'worked_time' => $request->input('worked_time', 0),
        //     // 'payment_status' => $request->input('payment_status', null),
        //     'description' => $request->input('description', null),
        //     'started_at' => $dateStart,
        //     'archive' => $request->input('archive', false),
        //     'archived_at' => $request->input('archived_at', null),

        // ]);

        // return $project->load('owner');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get customer
        $payment = Payment::findOrFail($id);

        if($payment->delete()){
            return $payment;
        }
    }
}
