<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitPeople;
use App\Models\UnitPet;
use App\Models\UnitVehicle;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function getInfo($id)
    {
        $array = [
            'error' => ''
        ];

        $unit = Unit::find($id);

        if (empty($unit)) {
            return $array['error'] = 'Unidade Inexistente';
        }

        $people = UnitPeople::where('id_unit', $id)->get();

        foreach ($people as $key => $person) {

            $people[$key]['birthdate'] = date('d/m/Y', \strtotime($person['birthdate']));
        }

        $pets = UnitPet::where('id_unit', $id)->get();
        $vehicles = UnitVehicle::where('id_unit', $id)->get();

        $array['unit'] = $unit;
        $array['unit']['people'] = $people;
        $array['unit']['pets'] = $pets;
        $array['unit']['vehicles'] = $vehicles;

        return response()->json($array);
    }
}
