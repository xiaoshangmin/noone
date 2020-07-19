<?php

namespace app\model\Content;
use noone\Model;
class Video extends Model{

    public function getById(int $id)
    {
        $this->fetch('SELECT * FROM a');
        return $this->userLevelModel->getList(1);
        return [999];
    }
}