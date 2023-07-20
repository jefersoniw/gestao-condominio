<?php

namespace App\Http\Controllers;

use App\Models\Wall;
use App\Models\WallLike;
use Illuminate\Http\Request;

class WallController extends Controller
{
    public function getAll()
    {
        $array = [
            'error' => '',
            'list' => [],
        ];

        $user = auth()->user();

        $walls = Wall::all();

        foreach ($walls as $wallKey => $wall) {
            $walls[$wallKey]['likes'] = 0;
            $walls[$wallKey]['liked'] = false;

            $likes = WallLike::where('id_wall', $wall['id'])->count();
            $walls[$wallKey]['likes'] = $likes;

            $liked = WallLike::where('id_wall', $wall['id'])->where('id_user', $user['id'])->count();
            if ($liked > 0) {
                $walls[$wallKey]['liked'] = true;
            }
        }

        $array['list'] = $walls;

        return $array;
    }
}
