<?php

namespace App\Http\Models;

use App\Http\Entities\Employe;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeUnit\CodeUnit;

class HomeModel extends Model
{
    public function getListEmploye()
    {
        $whereData = [
            'active' => 1,
            'delete' => 0
        ];

        $data['data'] = Employe::where($whereData)->get();
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
}

/*
try {
    DB::beginTransaction();
    // database queries here
    DB::commit();
} catch (\PDOException $e) {
    // Woopsy
    DB::rollBack();
}
*/
