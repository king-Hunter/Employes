<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\HomeModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $modelo;

    public function __construct()
    {
        $this->modelo = new HomeModel();
        $this->middleware('auth');
    }

    
    public function index()
    {
        $item = $this->modelo->getListEmploye();
        return view('home',  compact('item'));        
    }
}
