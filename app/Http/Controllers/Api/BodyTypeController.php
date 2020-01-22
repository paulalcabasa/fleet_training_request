<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use Storage;
use App\BodyType;

class BodyTypeController extends Controller
{
    public function index()
    {
        return response()->json(BodyType::where('status','active')->get());
    }

  
}
