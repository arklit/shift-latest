<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainPageController extends Controller
{
    /**
     * Роут главной страниы
     * @route /
     * @method GET
     */
    public function index(Request $request)
    {
        return view('welcome');
    }
}
