<?php

namespace App\Http\Controllers;

use App\Jobs\ParseJob;

class ParseController extends Controller
{
    public function parse() {
        return dispatch_now(new ParseJob());
    }
}
