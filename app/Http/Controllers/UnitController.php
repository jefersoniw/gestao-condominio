<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPersonRequest;
use App\Http\Requests\AddPetRequest;
use App\Http\Requests\AddVehicleRequest;
use App\Http\Requests\removePersonRequest;
use App\Http\Requests\removePetRequest;
use App\Http\Requests\removeVehicleRequest;
use App\Models\Unit;
use App\Models\UnitPeople;
use App\Models\UnitPet;
use App\Models\UnitVehicle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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

    public function addPerson($id, AddPersonRequest $request)
    {
        $array = [
            'error' => ''
        ];

        DB::beginTransaction();

        try {

            $unit = Unit::find($id);

            if (empty($unit)) {
                return $array['error'] = 'Unidade Inexistente';
            }

            $person = new UnitPeople();
            $person->id_unit = $unit->id;
            $person->name = $request->name;
            $person->birthdate = $request->birthdate;
            if (!$person->save()) {
                throw new Exception("erro ao salvar pessoas a propriedade");
            }

            DB::commit();

            $people = UnitPeople::where('id_unit', $id)->get();

            foreach ($people as $key => $person) {
                $people[$key]['birthdate'] = date('d/m/Y', \strtotime($person['birthdate']));
            }

            $array['unit'] = $unit;
            $array['unit']['people'] = $people;
        } catch (Exception $error) {
            DB::rollBack();

            $error = [
                'error' => true,
                'erro_msg' => $error->getMessage()
            ];

            return $array['error'] = $error;
        };

        return $array;
    }

    public function addVehicle($id, AddVehicleRequest $request)
    {
        $array = [
            'error' => ''
        ];

        DB::beginTransaction();

        try {

            $unit = Unit::find($id);

            if (empty($unit)) {
                return $array['error'] = 'Unidade Inexistente';
            }

            $vehicle = new UnitVehicle();
            $vehicle->id_unit = $unit->id;
            $vehicle->title = $request->title;
            $vehicle->color = $request->color;
            $vehicle->plate = $request->plate;
            if (!$vehicle->save()) {
                throw new Exception("erro ao salvar veículos a propriedade");
            }

            DB::commit();

            $vehicles = UnitVehicle::where('id_unit', $id)->get();

            $array['unit'] = $unit;
            $array['unit']['vehicles'] = $vehicles;
        } catch (Exception $error) {
            DB::rollBack();

            $error = [
                'error' => true,
                'erro_msg' => $error->getMessage()
            ];

            return $array['error'] = $error;
        };

        return $array;
    }

    public function addPet($id, AddPetRequest $request)
    {
        $array = [
            'error' => ''
        ];

        DB::beginTransaction();

        try {

            $unit = Unit::find($id);

            if (empty($unit)) {
                return $array['error'] = 'Unidade Inexistente';
            }

            $pet = new UnitPet();
            $pet->id_unit = $unit->id;
            $pet->name = $request->name;
            $pet->race = $request->race;
            if (!$pet->save()) {
                throw new Exception("erro ao salvar pets a propriedade");
            }

            DB::commit();

            $pets = UnitPet::where('id_unit', $id)->get();

            $array['unit'] = $unit;
            $array['unit']['pets'] = $pets;
        } catch (Exception $error) {
            DB::rollBack();

            $error = [
                'error' => true,
                'erro_msg' => $error->getMessage()
            ];

            return $array['error'] = $error;
        };

        return $array;
    }

    public function removePerson($id, removePersonRequest $request)
    {
        $array = [
            'error' => ''
        ];

        try {

            $unit = Unit::find($id);

            if (empty($unit)) {
                return $array['error'] = 'Propriedade inexistente';
            }

            $person = UnitPeople::where('id', $request->idPerson)
                ->where('id_unit', $unit->id);

            if (empty($person)) {
                return $array['error'] = 'Pessoa inexistente';
            }

            $person->delete();

            $people = UnitPeople::where('id_unit', $unit->id)->get();

            $array['unit'] = $unit;
            $array['unit']['people'] = $people;
        } catch (Exception $error) {
            $error = [
                'error' => true,
                'erro_msg' => $error->getMessage()
            ];
        }

        return response()->json($array);
    }

    public function removePet($id, removePetRequest $request)
    {
        $array = [
            'error' => ''
        ];

        try {

            $unit = Unit::find($id);

            if (empty($unit)) {
                return $array['error'] = 'Propriedade inexistente';
            }

            $pet = UnitPet::where('id', $request->idPet)
                ->where('id_unit', $unit->id);

            if (empty($pet)) {
                return $array['error'] = 'Pet inexistente';
            }

            $pet->delete();

            $pets = UnitPet::where('id_unit', $unit->id)->get();

            $array['unit'] = $unit;
            $array['unit']['pets'] = $pets;
        } catch (Exception $error) {
            $error = [
                'error' => true,
                'erro_msg' => $error->getMessage()
            ];
        }

        return response()->json($array);
    }

    public function removeVehicle($id, removeVehicleRequest $request)
    {
        $array = [
            'error' => ''
        ];

        try {

            $unit = Unit::find($id);

            if (empty($unit)) {
                return $array['error'] = 'Propriedade inexistente';
            }

            $vehicle = UnitVehicle::where('id', $request->idVehicle)
                ->where('id_unit', $unit->id);

            if (empty($vehicle)) {
                return $array['error'] = 'Veículo inexistente';
            }

            $vehicle->delete();

            $vechicles = UnitVehicle::where('id_unit', $unit->id)->get();

            $array['unit'] = $unit;
            $array['unit']['vechicles'] = $vechicles;
        } catch (Exception $error) {
            $error = [
                'error' => true,
                'erro_msg' => $error->getMessage()
            ];
        }

        return response()->json($array);
    }
}
