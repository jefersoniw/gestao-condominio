<?php

namespace App\Http\Controllers;

use App\Models\Billet;
use Illuminate\Http\Request;

class BilletController extends Controller
{
    public function getAll(Request $request)
    {
        $array = [
            'error' => ''
        ];

        $unit = $request->input('unit');

        if (!$unit) {
            return $array['error'] = 'A unidade é obrigatória';
        }

        $billets = Billet::where('id_unit', $unit)->get();

        foreach ($billets as $billetKey => $billet) {

            $billets[$billetKey]['fileUrl'] = asset('storage/billet/' . $billet['fileUrl']);
        }

        $array['billets'] = $billets;

        return $array;
    }
}
