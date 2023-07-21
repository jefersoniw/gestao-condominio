<?php

namespace App\Http\Controllers;

use App\Models\Billet;
use App\Models\Unit;
use Illuminate\Http\Request;

class BilletController extends Controller
{
    public function getAll(Request $request)
    {
        $array = [
            'error' => ''
        ];

        $user = auth()->user();
        $unit = $request->input('unit');

        if (!$unit) {
            return $array['error'] = 'A unidade é obrigatória';
        }

        $myUnit = Unit::where('id', $unit)->where('id_owner', $user['id'])->count();

        if ($myUnit == 0) {
            return $array['error'] = 'A unidade não é sua!';
        }

        $billets = Billet::where('id_unit', $unit)->get();

        foreach ($billets as $billetKey => $billet) {

            $billets[$billetKey]['fileUrl'] = asset('storage/billet/' . $billet['fileUrl']);
        }

        $array['billets'] = $billets;

        return $array;
    }
}
