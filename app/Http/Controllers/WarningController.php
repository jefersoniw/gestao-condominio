<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarningRequest;
use App\Models\Unit;
use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WarningController extends Controller
{
    public function getMyWarning(Request $request)
    {
        $array = [
            'error' => ''
        ];

        $property = $request->property;
        if (!$property) {
            return $array['error'] = 'A propriedade é necessária!';
        }

        $user = auth()->user();

        $unit = Unit::where('id', $property)
            ->where('id_owner', $user['id'])
            ->count();

        if ($unit == 0) {
            return $array['error'] = 'Essa unidade não é sua!';
        }

        $warnings = Warning::where('id_unit', $property)
            ->orderBy('dateCreated', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($warnings as $warningKey => $warning) {

            $warnings[$warningKey]['dateCreated'] = date('d-m-Y', strtotime($warning['dateCreated']));
            $photoList = [];
            $photos = \explode(',', $warning['photos']);

            foreach ($photos as $photoKey => $photo) {

                if (!empty($photo)) {
                    $photoList[] = asset('storage/' . $photo);
                }
            }

            $warnings[$warningKey]['photoList'] = $photoList;
        }

        $array['list'] = $warnings;


        return $array;
    }

    public function addWarningFile(WarningRequest $request)
    {
        $array = [
            'error' => ''
        ];

        $file = $request->photo->store('public');

        $array['photo'] = asset(Storage::url($file));

        return $array;
    }

    public function setWarning(Request $request)
    {
    }
}
