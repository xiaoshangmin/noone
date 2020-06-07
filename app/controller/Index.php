<?php

namespace app\controller;

use noone\Response;

class Index
{

    public function index()
    {
        return Response::create(['code' => 200, 'msg' => 'ok', 'data' => [
            'id' => 1,
        ]]);
    }
}
