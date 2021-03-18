<?php

namespace App\Http\Models;

use App\Http\Entities\Employe;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\This;
use SebastianBergmann\CodeUnit\CodeUnit;

class HomeModel extends Model
{
    public function getListEmploye()
    {
        $data['data'] = Employe::where('delete', 0)->get();
        $data['info'] = count($data['data']);
        return $data;
    }


    public function webServices()
    {
        $client = new Client();
        $url = "https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos";

        $headers = [
            'Bmx-Token' => '0fce7d85c76c9afa6f8ef05f95d50a11c62573c637d28706e28f2a81e84264e5'
        ];

        $response = $client->request('GET', $url, [
            // 'json' => $params,
            'headers' => $headers,
            'verify'  => false,
        ]);
        $data = json_decode($response->getBody());

        foreach ($data->bmx->series as $key => $value) {
            $objectData['idSerie'] =  $value->idSerie;
            $objectData['titulo'] = $value->titulo;
            $objectData['today'] = $value->datos;
        }
        $objectData['today'] = end($objectData['today']);
        return  $objectData;
    }

    private function codeGenerate()
    {
        $variabls = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lenthVariabls = strlen($variabls);
        $randomCode = '';
        for ($i = 0; $i < 15; $i++) {
            $randomCode .= $variabls[rand(0, $lenthVariabls - 1)];
        }
        return $randomCode;
    }
    private function coinType($coin)
    {
        switch ($coin->typeSalary) {
            case 'MXN':
                $object = array(
                    "from_currency" => "MXN",
                    "to_currency" => "USD",
                    "amount" => $coin->salary
                );
                break;

            case 'USD':
                $object = array(
                    "from_currency" => "USD",
                    "to_currency" => "MXN",
                    "amount" => $coin->salary
                );
                break;

            default:
                return null;
                break;
        }
        $preobj = json_encode($object);
        return $this->salaryCurrency($preobj);
    }
    private function salaryCurrency($object)
    {
        $salary = json_decode($object);
        $newSalary =  $this->currency($object);
        $newCurrency = array(
            $salary->from_currency => $salary->amount,
            $salary->to_currency => $newSalary
        );
        return json_decode(json_encode($newCurrency));
    }

    private function currency($valueCurrency)
    {
        $currency = json_decode($valueCurrency);
        $amount = $currency->amount;

        $apikey = 'eeeab53f0324c1b3c137';

        $from_Currency = urlencode($currency->from_currency);
        $to_Currency = urlencode($currency->to_currency);
        $query = "{$from_Currency}_{$to_Currency}";

        $url = "https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}";

        $json = file_get_contents($url);

        $obj = json_decode($json, true);
        $val = $obj["$query"];

        return $val * $amount;
    }


    public function saveNew($data)
    {
        try {
            DB::beginTransaction();
            $salary =  $this->coinType($data);
            $codeUnic = $this->codeGenerate();
            while (Employe::where('code', $codeUnic)->count() > 0) {
                $codeUnic = $this->codeGenerate();
            }
            Employe::create([
                'code' => $codeUnic,
                'name' => $data->name,
                'salary_dollar' => $salary->USD,
                'salary_pesos' => $salary->MXN,
                'address' => $data->address,
                'state' => $data->state,
                'city' => $data->city,
                'phone' => $data->phone,
                'email' => $data->email,
                'active' => 1,
                'delete' => 0
            ]);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }
    public function inactiveEmploye($id)
    {
        try {
            DB::beginTransaction();
            $data = Employe::find($id);
            $data->delete = 1;
            $data->save();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }
    public function changeStatus($id)
    {
        try {
            DB::beginTransaction();
            $data = Employe::find($id);
            if ($data->active) {
                $data->active = 0;
                $value['status'] = false;
            } else {
                $data->active = 1;
                $value['status'] = true;
            }
            $data->save();
            DB::commit();
            $value['data'] = true;
            return $value;
        } catch (\Throwable $th) {
            DB::rollback();
            $value['data'] = false;
            return $value;
        }
    }
    public function getDataEmployee($id)
    {
        try {
            $data = Employe::find($id);
            $salaryNew = $this->dateSalary($data->salary_pesos);
            $value['success'] = true;
            $value['data'] = $data;
            $value['data']['salary_dollar_to_6_mon'] = $salaryNew->USD;
            $value['data']['salary_pesos_to_6_mon'] = $salaryNew->MXN;
        } catch (\Throwable $th) {
            $value['data'] = $th;
            $value['success'] = false;
        }
        return $value;
    }
    private function dateSalary($salary)
    {
        $pemon = ((($salary / 100) * 5) * 6) + $salary;
        $object = array(
            "typeSalary" => "MXN",
            "salary" => $pemon,
        );
        $preobj = json_decode(json_encode($object));
        return $this->coinType($preobj);
    }
    public function getByEmployee($id)
    {
        return Employe::find($id);
    }
    public function typeSalary($type, $employe)
    {
        switch ($type) {
            case 'MXN':
                $currencySalary = $employe->salary_pesos;
                break;
            case 'USD':
                $currencySalary = $employe->salary_dollar;
                break;
            default:
                return null;
                break;
        }
        return $currencySalary;
    }
    public function saveEdit($data, $id)
    {
        try {
            DB::beginTransaction();
            $employe = Employe::find($id);
            if ($data->salary != null) {
                $salary = $this->coinType($data);
            } else {
                $salaryCurrency = $this->typeSalary($data->typeSalary, $employe);
                $data->salary = $salaryCurrency;
                $salary = $this->coinType($data);
            }
            $employe->name = $data->name;
            $employe->salary_dollar = $salary->USD;
            $employe->salary_pesos = $salary->MXN;
            $employe->address = $data->address;
            $employe->state = $data->state;
            $employe->city = $data->city;
            $employe->phone = $data->phone;
            $employe->email = $data->email;
            $employe->save();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }
}
