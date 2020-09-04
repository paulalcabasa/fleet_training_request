<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Image;
use Storage;
use App\UnitModel;
use App\Http\Requests;
use App\UnitModelBodyType;
use App\UnitModelEmissionType;
use DB;

class UnitModelController extends Controller
{
    public function index()
    {
        return response()->json(UnitModel::all());
    }

    public function show($unit_model_id)
    {
        return response()->json([
            'model' => UnitModel::findOrFail($unit_model_id),
            'body_types' => UnitModelBodyType::with('body_type')
                                ->where('unit_model_id', $unit_model_id)
                                ->get(),
            'emission_standards' => UnitModelEmissionType::with('emission_standard')
                                ->where('unit_model_id', $unit_model_id)
                                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
			'model_name' => 'required|string',
			'description' => 'required|string',
            'image' => 'required',
            'sequence_no' => 'required'
		]);

        if ($request->get('image')) {
            $image = $request->get('image');
            $name = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/unit_models/').$name);
        }

        $query = new UnitModel;
		$query->model_name = $request->model_name;
		$query->description = $request->description;
		$query->sequence_no = $request->sequence_no;
		$query->image = $name;
        $query->save(); 
        
        if(!empty($request->bodyTypes)){
            
            foreach($request->bodyTypes as $bodyType){
                $unitBody = new UnitModelBodyType;
                $unitBody->unit_model_id = $query->unit_model_id;
                $unitBody->body_type_id = $bodyType['id'];
                $unitBody->save();
            }
        }  

        if(!empty($request->bodyTypes)){
            
            foreach($request->bodyTypes as $bodyType){
                $unitBody = new UnitModelBodyType;
                $unitBody->unit_model_id = $query->unit_model_id;
                $unitBody->body_type_id = $bodyType['id'];
                $unitBody->save();
            }
        }

        if(!empty($request->emissionStandards)){
            
            foreach($request->emissionStandards as $emissionStandard){
                $emissionStandard = new UnitModelEmissionType;
                $emissionStandard->unit_model_id = $query->unit_model_id;
                $emissionStandard->emission_standard_id = $emissionStandard['id'];
                $emissionStandard->save();
            }
        }
      
		return response()->json($query);
    }

    public function update(Request $request, $unit_model_id)
    {
        $this->validate($request, [
			'model_name' => 'required|string',
            'description' => 'required|string',
            'sequence_no' => 'required'
		]);

        $query = UnitModel::findOrFail($unit_model_id);
		$query->model_name = $request->model_name;
        $query->description = $request->description;
        $query->sequence_no = $request->sequence_no;

        if ($request->get('image')) {
            $old_image = $query->image;
            $image = $request->get('image');
            $name = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            // $name = 'prince';
            Image::make($request->get('image'))->save(public_path('images/unit_models/').$name);
            $query->image = $name;
            Storage::disk('unit_models')->delete($old_image);
        }

        if(!empty($request->bodyTypes)){
            DB::table('unit_model_body_types')
                ->where('unit_model_id', $unit_model_id)
                ->delete();
            
            foreach($request->bodyTypes as $bodyType){
                $unitBody = new UnitModelBodyType;
                $unitBody->unit_model_id = $unit_model_id;
                $unitBody->body_type_id = $bodyType['id'];
                $unitBody->save();
            }
        }   

        if(!empty($request->emissionStandards)){
            DB::table('unit_model_emission_types')
                ->where('unit_model_id', $unit_model_id)
                ->delete();

            foreach($request->emissionStandards as $row){
                $emissionStandard = new UnitModelEmissionType;
                $emissionStandard->unit_model_id = $unit_model_id;
                $emissionStandard->emission_standard_id = $row['id'];
                $emissionStandard->save();
            }
        }
        
		$query->save();

		return response()->json($query);
    }

    public function delete($unit_model_id)
    {
        $query = UnitModel::findOrFail($unit_model_id);

        if (Storage::disk('unit_models')->delete($query->image)) {
            $query->delete();
        }

		return response()->json($query);
    }
}
