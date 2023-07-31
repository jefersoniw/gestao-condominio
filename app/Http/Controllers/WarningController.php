<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarningFileRequest;
use App\Http\Requests\WarningRequest;
use App\Models\Unit;
use App\Models\Warning;
use Exception;
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

    public function addWarningFile(WarningFileRequest $request)
    {
        $array = [
            'error' => ''
        ];

        $file = $request->photo->store('public');

        $array['photo'] = asset(Storage::url($file));

        return response()->json($array);
    }

    public function setWarning(WarningRequest $request)
    {
        $array = [
            'error' => ''
        ];

        try {

            $warning = new Warning();
            $warning->id_unit = $request->property;
            $warning->title = $request->title;
            $warning->status = 'IN_REVIEW';
            $warning->dateCreated = date('Y-m-d');

            if ($request->list && \is_array($request->list)) {
                $photos = [];

                foreach ($request->list as $listItem) {
                    $url = \explode('/', $listItem);
                    $photos[] = end($url);
                }

                $warning->photos = \implode(',', $photos);
            } else {
                $warning->photos = '';
            }

            if (!$warning->save()) {
                throw new Exception("erro ao salvar warning!");
            }

            $array['resultado'] = $warning;

            return response()->json($array);
        } catch (Exception $error) {

            return response()->json([
                'error' => true,
                'error_msg' => $error->getMessage(),
                'error_line' => $error->getLine()
            ]);
        }
    }
}
