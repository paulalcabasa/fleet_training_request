<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use Storage;
use App\EmissionStandard;

class EmissionStandardController extends Controller
{
    public function index()
    {
        return response()->json(EmissionStandard::where('status','active')->get());
    }


}
