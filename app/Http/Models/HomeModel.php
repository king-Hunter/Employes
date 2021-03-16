<?php

namespace App\Http\Models;
use App\Http\Entities\Employe;
use Illuminate\Database\Eloquent\Model;

class HomeModel extends Model
{
    public function getListEmploye(){
        $whereData = [
        'active' => 1,
        'delete' => 0];
        
        $data['data'] = Employe::where($whereData)->get(); 
        $data['info'] = count($data['data']);
        return $data;
    }    
}
