<?php

namespace App\Http\Controllers;

use App\Models\Doc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocController extends Controller
{
    public function getAll()
    {
        $array = [
            'error' => ''
        ];

        $docs = Doc::all();

        foreach ($docs as $docKey => $doc) {

            $docs[$docKey]['fileUrl'] = asset('storage/' . $doc['fileUrl']);
        }

        $array['docs'] = $docs;

        return $array;
    }
}
