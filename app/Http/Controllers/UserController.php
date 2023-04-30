<?php

namespace MyPluginNamespace\App\Http\Controllers;
use MyPluginNamespace\WaxFramework\Routing\Response;

class UserController extends Controller
{
    public function index( Validator $validator ) {
        $validator->validate(
            [
            // 'name' => 'required',
                'time' => 'required|date:Y-m-d|date_equals:2013-12-2'
            ]
        );

        return Response::send( $validator->errors, 403 );
    }
}