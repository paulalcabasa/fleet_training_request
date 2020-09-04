<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use Storage;
use App\UnitModelEmissionType;

class EmissionStandardController extends Controller
{
    public function index(Request $request)
    {   
        $unit_model_id = $request->unit_model_id;
        return response()->json(
            UnitModelEmissionType::with('emission_standard')
                ->where('unit_model_id', $unit_model_id)
                ->get()
        );
    }


}
