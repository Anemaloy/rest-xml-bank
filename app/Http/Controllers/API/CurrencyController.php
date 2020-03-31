<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;

class CurrencyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function getValute () {
        $result = Currency::getValute();
        return $result;
    }

    public function fillBD(Request $request) {
        $result = Currency::fillBD($request);
        return $result;
    }

    public function getFilter(Request $request) {
        $result = Currency::getFilter($request);
        return $result;
    }

}
