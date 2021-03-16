<?php

namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{

    protected $table = 'employe';
    protected $fillable = ['code', 'name', 'salary_dollar', 'salary_pesos', 'address', 'state', 'city', 'phone', 'email', 'active', 'delete'];
}
