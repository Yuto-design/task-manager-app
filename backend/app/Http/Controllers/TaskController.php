<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json([
            ['id' => 1, 'title' => 'sample task']
        ]);
    }
}
