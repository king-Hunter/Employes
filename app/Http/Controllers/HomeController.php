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
    public function getAddNew()
    {
        $item = $this->modelo->webServices();
        return view('form',  compact('item'));
    }
    public function postAddNew(Request $request)
    {
        $reponse = $this->modelo->saveNew($request);
        if ($reponse) {
            return redirect()->route('home')->with('success', 'Incorporated employees');
        }
        return redirect()->route('home')->with('error', 'Error occurred');
    }
    public function deleteEmploye(Request $request)
    {
        $validate = $this->modelo->inactiveEmploye($request->id);
        if ($validate) {
            $return_json = [
                'success' => true,
                'msg' => 'Se elimino con exito'
            ];
        } else {
            $return_json = [
                'success' => false,
                'msg' => 'Error no se puedo eliminar'
            ];
        }
        return $return_json;
    }
    public function statusEmploye(Request $request)
    {
        $validate = $this->modelo->changeStatus($request->id);
        if ($validate['data']) {
            $return_json = [
                'success' => true,
                'status' => $validate['status'],
                'msg' => 'Se elimino con exito'
            ];
        } else {
            $return_json = [
                'success' => false,
                'msg' => 'Error no se puedo eliminar'
            ];
        }
        return $return_json;
    }
    public function viewEmploye(Request $request)
    {
        $validate = $this->modelo->getDataEmployee($request->id);
        return  [
            'success' => $validate['success'],
            'data' => $validate['data']
        ];
    }
}
