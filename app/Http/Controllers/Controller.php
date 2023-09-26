<?php

namespace App\Http\Controllers;

use App\Traits\ResponseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ResponseService, AuthorizesRequests, ValidatesRequests;
}
