<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\storeEmployeeRequest;
use App\Http\Requests\updateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $employees = Employee::with('department')->get();
        foreach($employees as  $employee){
                $fullName =$employee->FullName;
        }
        return response()->json([
          'status' =>'List_of_Employee',
          'employee'=>$employees,
          'FullName' => $fullName,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeEmployeeRequest $request)
    {
        //
        $request->validated();

        try
        {
            DB::beginTransaction();
            $employee = Employee::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'email'=>$request->email,
            'department_id'=>$request->department_id,
            'position'=>$request->position,

            ]);
            //Add Project
            if($request->project_id)
            {

                $employee->projects()->attach($request->project_id);
            }
            //Add notes
            if($request->notes){

            $employee->notes()->create([
             'notes'=>$request->notes,
            ]);
        }
            DB::commit();
            return response()->json([
                'status'=>'add',
                 'employee' =>$employee,
                 'project' =>$employee->projects,
                 'notes'=>$employee->notes,
            ]);
        }
        catch(Throwable $th){
          DB::rollbacK();
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
    public function show(Employee $employee)
    {
        //
        return response()->json([
            'sttaus'=>'show',
            'employee'=>$employee,
            'FullName'=>$employee->FullName,
            'department'=>$employee->department,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateEmployeeRequest $request, Employee $employee)
    {
        //
        $newData =[];
        try{
        DB::beginTransaction();
           if(isset($request->first_name)){
               $newData['first_name'] = $request->first_name;
           }
           if(isset($request->last_name)){
            $newData['last_name'] = $request->last_name;
        }
        if(isset($request->email)){
            $newData['email'] = $request->email;
        }
        if(isset($request->department_id)){
            $newData['department_id'] = $request->department_id;
        }
        if(isset($request->position)){
            $newData['position'] = $request->position;
        }
        //update project if found (many to Many)
        if($request->project_id){
            $employee->projects()->sync($request->project_id);
        }
        //update Notes
        if($request->notes){

            $employee->notes()->updateorCreate([
             'notes'=>$request->notes,
            ]);
        }
        DB::commit();
        $employee->update($newData);
        return response()->json([
            'status' =>'update',
            'Employee' =>$employee,
            'project'=>$employee->projects,
            'notes'=>$employee->notes,

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
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
        if($employee){
          $projects =  $employee->projects;
          if($projects){
            $employee->projects()->detach();
          }
          $employee->delete();
          return response()->json([
            'status'=>'Delete'
        ]);
        }

    }
    //Restore
      public function Restore($id){
          $employee = Employee::onlyTrashed()->find($id);
          $employee->restore();
          return response()->json([
            'status' =>'restore',
            'employee' => $employee,
        ]);
     }
//ForceDelete
  public function ForceDelete($id){
    $employee= Employee::withTrashed()->find($id);
    //delete notes if found
    $notes = $employee->notes;
    if($notes){
        $employee->notes()->delete();

    }
    $employee->forceDelete();
    return response()->json([
        'Delete'=>'الحذف بشكل كلي'
    ]);
}
//get Note && department of employee
      public function getDepartmentNotes($id){
        $employee = Employee::find($id);
         $notes=$employee->notes;
         $department=$employee->department;
        return response()->json([
           'status'=>'Done',
           'employee' =>$employee,
        ]);
   }


}
