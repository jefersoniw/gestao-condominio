<?php

namespace App\Http\Controllers;

use App\Models\FoundAndLost;
use Illuminate\Http\Request;

class FoundAndLostController extends Controller
{
    public function getAll()
    {
        $array = [
            'error' => ''
        ];

        $lost = FoundAndLost::where('status', 'LOST')
            ->orderBy('dateCreated', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $recovered = FoundAndLost::where('status', 'RECOVERED')
            ->orderBy('dateCreated', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($lost as $lostKey => $lostValue) {

            $lost[$lostKey]['dateCreated'] = date('d/m/Y', \strtotime($lostValue['dateCreated']));
            $lost[$lostKey]['photo'] = asset('storage/' . $lostValue['photo']);
        }

        foreach ($recovered as $rectKey => $recValue) {

            $recovered[$rectKey]['dateCreated'] = date('d/m/Y', \strtotime($recValue['dateCreated']));
            $recovered[$rectKey]['photo'] = asset('storage/' . $recValue['photo']);
        }

        $array['lost'] = $lost;
        $array['recovered'] = $recovered;

        return response()->json($array);
    }
}
