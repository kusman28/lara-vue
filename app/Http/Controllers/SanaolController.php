<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SanaolController extends Controller
{
    public function sanaol() {
        // return dd('Sanaol');
        return response()->json([
            'message' => 'ASD'
        ]);
    }
}
