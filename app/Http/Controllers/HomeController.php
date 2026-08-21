<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the Noksha welcome homepage.
     */
    public function index()
    {
        return view('welcome');
    }
}
