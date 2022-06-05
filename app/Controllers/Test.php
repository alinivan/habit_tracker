<?php

namespace App\Controllers;

use Core\Request;

class Test
{
    public function test(Request $request) {
        pre($request->get('test'));
    }

    public function test2(Request $request) {
        pre($request->get('test'));
    }

}