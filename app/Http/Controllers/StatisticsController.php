<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use App\Customer;
use Carbon\Carbon;
use App\User;
use App\Sprint;
use App\ExchangeRate;
use App\Currency;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return Payment::with('customer')->get();
    //     //return Payment::all();
    // }

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
    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'customer' => 'required',
    //         'amount' => 'required',
    //     ]);

    //     $input = $request->all();

    //     $dateFrom = $input['date_from']
    //             ? \Carbon\Carbon::parse($input['date_from'])->tz('UTC'):
    //             \Carbon\Carbon::now()->tz('UTC');
    //     $dateTo = $input['date_to']
    //             ? \Carbon\Carbon::parse($input['date_to'])->tz('UTC'):
    //             \Carbon\Carbon::now()->tz('UTC');

    //     $transaction = Customer::find($input['customer'])->payments()->create([
    //         'amount' => $request->input('amount'),
    //         'profit' => $request->input('amount'),
    //         'date_from' => $dateFrom,
    //         'date_to' => $dateTo,
    //         'description' => $request->input('description', null),
    //     ]);

    //     return $transaction;
    //     // $project = Customer::find($input['customer'])->projects()->create([
    //     //     // 'user_id' => $request->input('user_id'),
    //     //     // 'project_id' => $request->input('project'),
    //     //     'name' => $request->input('name'),
    //     //     'rate' => $request->input('rate'),
    //     //     'currency' => $request->input('currency', '$'),
    //     //     'rate_type' => $request->input('rate_type', 'hourly'),
    //     //     // 'worked_time' => $request->input('worked_time', 0),
    //     //     // 'payment_status' => $request->input('payment_status', null),
    //     //     'description' => $request->input('description', null),
    //     //     'started_at' => $dateStart,
    //     //     'archive' => $request->input('archive', false),
    //     //     'archived_at' => $request->input('archived_at', null),

    //     // ]);

    //     // return $project->load('owner');

    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $start = Carbon::parse($request->input('dateStart'));
        $end = Carbon::parse($request->input('dateEnd'));
        $payments = Payment::whereBetween('created_at', [$start, $end])->get();

        $totalIncome = 0;
        foreach($payments as $payment) {
            $totalIncome += $payment->amount;
        }

        $timeWorked = [];

        $totalExpense = 0;
        $totalTime = 0;
        $sprintsForPerios = Sprint::where('sprints.payment_status', 2)
                ->whereBetween('sprints.closed_at', [$start, $end])
                ->orWhere('sprints.closed_at', null)
                ->orWhere(function ($query) use($start,$end) {
                    $query->where('sprints.created_at', '<=', $end)
                          ->where('sprints.closed_at', '>=', $end);
                })
                ->with('currency')
                ->get();

        $totalExpense = 0;
        foreach($sprintsForPerios as $sprint) {
            if( $sprint->payment_status == 2) {
                $paymentDate = $sprint->closed_at ?
                    \Carbon\Carbon::parse($sprint->closed_at)->tz('UTC')
                    : \Carbon\Carbon::parse($sprint->updated_at)->tz('UTC');

                //$currency = Currency::find($sprint->input('currency'));
                //dd($paymentDate->toDateString());
                $rateCoefficient = 1;
                if($sprint->currency->name !== 'USD') {
                    $exchangeRate = ExchangeRate::where('fsym',$sprint->currency->name)
                        ->whereDate('created_at', '=', $paymentDate->toDateString())
                        ->get()
                        ->first();
                    if(!$exchangeRate) {
                        $exchangeRate = ExchangeRate::where('fsym',$sprint->currency->name)
                            ->latest('id')->first();
                    }

                    $rateCoefficient =  $exchangeRate->rate;
                }

                $totalExpense += $sprint->worked_time * $sprint->rate * $rateCoefficient;
            }

            if(!isset($timeWorked[$sprint->project->name])) {
                $timeWorked[$sprint->project->name]['time'] = 0;
                $timeWorked[$sprint->project->name]['customer'] = $sprint->project->owner->name;
            }
            $timeWorked[$sprint->project->name]['time'] += $sprint->worked_time;
            $totalTime += $sprint->worked_time;
        }

        $projects = [];
        foreach ($timeWorked as $projectName => $item) {
            $projects[] = [
                'project' => $projectName,
                'time' => $item['time'],
                'customer' => $item['customer'],
                'total' => $totalTime
            ];
        }


        // [$totalIncome, $totalExpence];
        //return false;
        $balance = number_format(($totalIncome-$totalExpense), 2, '.', '');
        $totalExpense = number_format($totalExpense,2,  '.', '');
        $totalIncome = number_format($totalIncome, 2 , '.', '');
        return [
            'income' => $totalIncome,
            'expense' => $totalExpense,
            'balance' => $balance,
            'projects' => $projects
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
    public function update(Request $request)
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
