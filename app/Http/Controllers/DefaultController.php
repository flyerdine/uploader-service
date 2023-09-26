<?php

namespace App\Http\Controllers;

class DefaultController extends Controller
{
    /**
     * Default action
     */
    public function index()
    {
        return $this->successResponse('Welcome to Uploder API');
    }
}
