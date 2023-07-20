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

    public function like($id)
    {
        $array = [
            'error' => ''
        ];

        $user = auth()->user();

        $likes = WallLike::where('id_wall', $id)
            ->where('id_user', $user['id'])
            ->count();

        if ($likes > 0) {

            WallLike::where('id_wall', $id)
                ->where('id_user', $user['id'])
                ->delete();

            $array['liked'] = false;
        } else {
            $wallLike = new WallLike();
            $wallLike->id_user = $user['id'];
            $wallLike->id_wall = $id;
            $wallLike->save();

            $array['liked'] = true;
        }

        $array['likes'] = WallLike::where('id_wall', $id)->count();

        return $array;
    }
}
