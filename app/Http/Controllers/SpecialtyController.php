<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function getSpecialties($doctorId)
    {
        return Specialty::whereHas('usersSpecialties', function ($query) use ($doctorId) {
            $query->where('user_id', $doctorId);
        })->get();
    }
}
