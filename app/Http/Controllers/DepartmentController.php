<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\storeDepartmentRequest;
use App\Http\Requests\updateDepartmentRequest;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $departments = Department::with('employees')->get();
        return response()->json([
            'status'=>'success',
            'department'=>$departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeDepartmentRequest $request)
    {
        //
        try {
            DB::beginTransaction();
$description=null;
            $department = Department::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
if($request->notes){
    $department->notes()->create([
        'notes'=>$request->notes
    ]);
}
            DB::commit();

            return response()->json([
                'status' => 'Add',
                'department' => $department,
                'notes' =>$department->notes,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::debug($th);
            $e = \Log::error($th->getMessage());

            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateDepartmentRequest $request, Department $department)
    {
        //
        $newData=[];

        try{

           DB::beginTransaction();
           if(isset($request->name)){
               $newData['name'] = $request->name;
           }
           if(isset($request->lastt_name)){
               $newData['description'] = $request->description;
           }
           if($request->notes){
            $department->notes()->updateorCreate([
                'notes'=>$request->notes
            ]);
        }
           DB::commit();
           $department->update($newData);
           return response()->json([
               'status' =>'update',
               'department' =>$department,

             ]);
            }


        catch(\Throwable $th){
           DB::rollback();
           Log::debug($th);
           Log::error($th->getMessage());
           return response()->json([
            'status'=>'error'
           ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //

        $department->delete();
        return response()->json([
            'status'=>'delete'
           ]);
    }
    //restore
    public function restore($id){
        $department= Department::onlyTrashed()->find($id);
       $department->restore();
        return response()->json([
            'status' =>'restore',
            'department' => $department,
        ]);
     }
     public function force($id){
        $department = department::withTrashed()->find($id);
        $notes = $department->notes;
        if($notes){
            $department->notes()->delete();
        }
        $department->forceDelete();
        return response()->json([
            'status' =>'delete بشكل نهائي',
            'department' => $department,
        ]);
     }
     //

 
}
