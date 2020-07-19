<?php

namespace app\model\User;

use noone\Model;
class Level extends Model{

    public function getList(int $id)
    {
        return [
            1,2,3,4,5,6,7
        ];
    }
}