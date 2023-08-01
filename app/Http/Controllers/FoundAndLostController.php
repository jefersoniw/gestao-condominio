<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoundAndLostRequest;
use App\Models\FoundAndLost;
use Exception;
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

    public function insert(FoundAndLostRequest $request)
    {
        $array = [
            'error' => ''
        ];

        try {
            $file = $request->photo->store('public');
            $file = \explode('public/', $file);
            $photo = $file[1];

            $lost = new FoundAndLost();
            $lost->status = 'LOST';
            $lost->photo = $photo;
            $lost->description = $request->description;
            $lost->where = $request->where;
            $lost->dateCreated = date('Y-m-d');
            if (!$lost->save()) {
                throw new Exception("erro ao salvar foundandlost");
            }

            return $array['lost'] = $lost;
        } catch (Exception $error) {

            $array['error'] = [
                'msg' => 'erro ao salvar foundandlost',
                'msg_error' => $error->getMessage()
            ];
        }

        return $array;
    }
}
