<?php

namespace App\Http\Controllers;

use App\Models\Status;

class StatusController extends Controller
{
    public function showStatus()
    {
        $status = Status::where('id', 1)->first();

        return response()->json($status);
    }
}
