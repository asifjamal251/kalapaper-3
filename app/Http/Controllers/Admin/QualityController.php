<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quality;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class QualityController extends Controller
{
    public function create(Request $request ){
        return view('admin.quality.create');
    }



    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:191|unique:qualities,name',
            'code' => 'required|string|max:191|unique:qualities,code',
        ]);

        $name = Str::of($validated['name'])->lower()->title();
        $code = Str::of($validated['code'])->upper();

        $quality = Quality::create([
            'name' => $name,
            'code' => $code,
        ]);


        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Quality Saved Successfully',
            'call_back' => '',
            'table_referesh' => true,
            'model_id' => 'dataSave'
        ]);
    }




    public function edit($id){
        $quality = Quality::findOrFail($id);
        return view('admin.quality.edit', compact('quality'));
    }




    public function update(Request $request, $id){
        $quality = Quality::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('qualities', 'name')->ignore($id),
            ],

            'code' => [
                'required',
                'string',
                'max:191',
                Rule::unique('qualities', 'code')->ignore($id),
            ],
        ]);

        $name = Str::of($validated['name'])->lower()->title();
        $code = Str::of($validated['code'])->upper();
        
        $quality->update([
            'name' => $name,
            'code' => $code,
        ]);

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Quality Updated Successfully',
            'call_back' => '',
            'table_referesh' => true,
            'model_id' => 'dataSave'
        ]);
    }



    public function destroy($id){
        $lock_type = Quality::findOrFail($id);
        $lock_type->delete();

        return response()->json(['message'=>'Payment Type deleted Successfully ...', 'class'=>'success']); 
    }
}
