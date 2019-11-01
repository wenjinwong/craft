<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;

class User extends Controller
{

    public function index()
    {
        $data['id'] = 101;
        $data['name'] = "jack";

        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
        $this->response()->write(json_encode($data));
    }

    public function test()
    {
        $this->response()->write("test method for the User Controller");
    }
}
