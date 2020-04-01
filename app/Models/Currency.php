<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $table = "currency";
    //
    public static function fillBD ($request) {
        Currency::truncate();
        $error = array();
        $success = true;
        $countDay = $request->count_day;
        for ($countDay; $countDay > 0; $countDay--) {
            
            $date = new \DateTime('-' . $countDay . ' days');
            $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $date->format('d.m.Y'); 
            $xml = file_get_contents($url);
            $feed = simplexml_load_string($xml);
         
            foreach ($feed as $k => $v) {
                $currecy = New Currency;
                $currecy->valuteID = (string)$v->attributes();
                $currecy->numCode = (int)$v->NumCode;
                $currecy->сharCode = (string)$v->CharCode;
                $currecy->value = floatval(str_replace(',', '.', (string)$v->Value));
                $currecy->date = $date->format('Y-m-d');
                $currecy->save();
            }
            
        }
        if (Currency::count() == 0) {
            $success = false;
        }
        return json_encode(['success' => true, 'errors' => $error]);
    }

    public static function getValute () {
        $valute = Currency::groupBy('valuteID')->get();
        return json_encode(['valute' => $valute]);
    }

    public static function getFilter ($request) {

        $filters = $request->all();
        $valute = Currency::where(function ($query) use ($filters) {
            foreach ($filters as $k => $v) {
                if ($k == 'valuteID') {
                    $query->where('valuteID', '=', $v);
                }
                if ($k == 'date_from') {
                    $query->where('date', '>', $v);
                }
                if ($k == 'date_to') {
                    $query->where('date', '<', $v);
                }
            }
        })->get();
        return json_encode(['valute' => $valute]);
    }
    

}
