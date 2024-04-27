<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Employee extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable =[
         'first_name',
         'last_name',
           'email',
           'department_id',
           'position',

    ];
    protected $hidden =[
        'pivot'
    ];


       //setter
       public function setFirstNameAttribute($value){
           return $this->attributes['first_name']=ucfirst($value);
       }

       public function setLastNameAttribute($value){
        return $this->attributes['last_name']=ucfirst($value);
    }
//getter
public function getFullNameAttribute(){

    return $this->first_name. " " .$this->last_name;
}
//one to many
public function department(){

    return $this->beLongsTo(Department::class);
  }
  //many To Many

    public function projects(){
        return $this->belongsToMany(Project::class);
    }

    //-------------------
    //morph
 public function notes(){
    return $this->morphMany(note::class,'notable');
 }
}
