<?php
/**
 * @Copyright (C), 2019-2020, 甲木公司
 * @Name User
 * @Author xiaoshangmin
 * @Version 1.0
 * 2020/6/5
 * @Description
 */


namespace app\controller;
use noone\{Response, Request,Controller};
class User  extends Controller
{

    public function index()
    {
        
        return $this->request->getAction();;
    }

}