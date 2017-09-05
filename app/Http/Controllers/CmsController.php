<?php

namespace App\Http\Controllers;

use App\Models\Cms;
use Illuminate\Http\Request;
use Validator;
 
 

class CmsController extends Controller
{
    public function index()
    {  
        return Cms::all();
    }

    public function show(Cms $cms)
    {    
        return $cms;
    } 
        

    public function store(Request $request)
    {            

        $return = [];
        $input = $request->all();
        $input = array_map('trim', $input);
        $validator = Validator::make($input, [
                    'name' => ['required', 'max:50', 'unique:roles'],
                    'level' => ['required', 'numeric']
        ]);
        if (!$validator->fails()) {
            $name = $input["name"];
            $input["slug"] = str_replace(' ', '.', trim(strtolower($name)));
            $role = Role::create($input);
            if (count($role) > 0) {
                $return = ['status' => 200, 'message' => 'Role has been created successfully', "class" => "alert alert-success", 'result' => $role];
            } else {
                $return = ['status' => 400, 'errors' => ['Something went wrong'], "class" => "alert alert-danger", 'result' => NULL];
            }
        } else {
            $error = setErrorMessages($validator->messages()->toArray());
            $return = ['status' => 400, 'errors' => $error, 'result' => NULL];
        }
        return $return;        
    }

    public function update(Request $request, Cms $cms)
    {
        $cms->update($request->all());
        return response()->json($cms);
    }

    public function delete(Cms $cms)
    {
        $cms->delete();
        return response()->json(null, 204);
    }

    public function getCmsByID($cmsSlug = NULL) {         
        $content = Cms::select('*')->where('slug',$cmsSlug)->get();
        if (count($content) > 0) {
            return response()->json($content, 200);           
        } else {
            return response()->json(NULL, 204);          
        }        
    }
}
