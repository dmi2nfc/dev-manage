<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Customer;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::with('owner', 'currency')->get();
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
            'name' => 'required',
            'rate' => 'required|integer|min:0'
        ]);


        $input = $request->all();

        $dateStart = $input['started_at']
                ? \Carbon\Carbon::parse($input['started_at'])->tz('UTC'):
                \Carbon\Carbon::now()->tz('UTC');

        $project = Customer::find($input['customer'])->projects()->create([
            // 'user_id' => $request->input('user_id'),
            // 'project_id' => $request->input('project'),
            'name' => $request->input('name'),
            'rate' => $request->input('rate'),
            'currency_id' => $request->input('currency', '1'),
            'rate_type' => $request->input('rate_type', 'hourly'),
            // 'worked_time' => $request->input('worked_time', 0),
            // 'payment_status' => $request->input('payment_status', null),
            'description' => $request->input('description', null),
            'started_at' => $dateStart,
            'archive' => $request->input('archive', false),
            'archived_at' => $request->input('archived_at', null),

        ]);

        return $project->load('owner');

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
        $project = Project::findOrFail($id);

        if($project->delete()){
            return $project;
        }
    }
}
