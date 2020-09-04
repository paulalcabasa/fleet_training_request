<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use Storage;
use App\BodyType;
use App\UnitModelBodyType;

class BodyTypeController extends Controller
{
    public function index(Request $request)
    {
        $unit_model_id = $request->unit_model_id;
        return response()->json(
            UnitModelBodyType::with('body_type')->where('unit_model_id', $unit_model_id)->get()
        );
    }

  
}
