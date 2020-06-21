<?php

namespace app\controller;

use noone\{Response, Request,Controller};
use app\model\Content\Video;


class Index extends Controller
{

    public function index(Request $req)
    {
        $id = (new Video())->getById(9999);
        
        return $this->config['cache']['redis'][0];
        // return Response::create([
        //     'code' => 200, 'msg' => 'ok', 'data' => [
        //         'id' => $id,
        //     ]
        // ]);
    }

}
