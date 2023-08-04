<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoundAndLostController extends Controller
{
    public function getAll()
    {
        $array = [
            'error' => ''
        ];



        return response()->json($array);
    }
}
