<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use Storage;
use App\BodyType;

class BodyTypeController extends Controller
{
    public function index(Request $request)
    {
        $unit_model_id = $request->unit_model_id;
        return response()->json(
            BodyType::where([
                ['status','active'],
                ['unit_model_id', $unit_model_id],
            ])->get());
    }

  
}
