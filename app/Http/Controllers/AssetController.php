<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Store a newly created creator asset in storage.
     */
    public function store(Request $request)
    {
        return app(ResourceController::class)->store($request);
    }
}
