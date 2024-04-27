<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Department extends Model
{
    use HasFactory , SoftDeletes;

    protected $table ='departments';

    protected $fillable =[
        
     'name',
     'description',
    ];

    //one to many
       public function employees(){
        return $this->hasMany(Employee::class);
       }
         //morph
 public function notes(){
    return $this->morphMany(note::class,'notable');
 }
}
