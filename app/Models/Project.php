<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable =[
        'name'
    ];
    
    //many To Many

    public function Employees(){

        return $this->beLongsToMany(Employee::class);

    }
}
