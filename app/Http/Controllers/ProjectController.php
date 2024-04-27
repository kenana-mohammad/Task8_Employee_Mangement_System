<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $projects = Project::with('Employees')->get();
        return response()->json([
            'status'=>"list of Project",
            'project' =>$projects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            DB::beginTransaction();
            $project = Project::create([
                'name'=>$request->name,
            ]);
            DB::commit();
            return response()->json([
                'status' =>"Add",
                'project' =>$project
            ]);
            }
            catch(Throwable $th){
                DB::rollback();
                Log::debug($th);
                Log::error($th->getMessage());
                return response()->json([
                 'status'=>'error'
                ]);

            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
