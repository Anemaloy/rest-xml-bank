<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $table = "currency";
    public $timestamps = false;
    //
    public static function fillBD ($request) {
        Currency::truncate();
        $error = array();
        $countDay = $request->count_day;
        for ($countDay; $countDay > 0; $countDay--) {
            
            $date = new \DateTime('-' . $countDay . ' days');
            $xml = new \DOMDocument();
            $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $date->format('d.m.Y');
        
            if (@$xml->load($url))
            {
                $root = $xml->documentElement;
                $items = $root->getElementsByTagName('Valute');
            
                foreach ($items as $k => $item)
                { 
                    $currecy = New Currency;
                    $currecy->valuteID = $item->getAttribute('ID');
                    $currecy->numCode = $item->getElementsByTagName('NumCode')->item(0)->nodeValue;
                    $currecy->сharCode = $item->getElementsByTagName('CharCode')->item(0)->nodeValue;
                    $currecy->value = floatval(str_replace(',', '.', $item->getElementsByTagName('Value')->item(0)->nodeValue));
                    $currecy->date = $date->format('Y-m-d');
                    $currecy->save();
                }
            } else {
                $error[] = 'day' . $date->format('Y-m-d') . 'not parsing';
            }
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
