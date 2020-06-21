<?php

namespace app\controller;

use noone\{Response, Request};
use app\model\Content\Video;


class Index
{

    public function index(Request $req)
    {
        $id = (new Video())->getById(9999);
        return $id;
        // return Response::create([
        //     'code' => 200, 'msg' => 'ok', 'data' => [
        //         'id' => $id,
        //     ]
        // ]);
    }
}
