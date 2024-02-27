<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Certificate;

class CertificatesController extends Controller
{
    use ApiResponseTrait;
    
    public function __construct()
    {
      //  $this->middleware('auth:api');
    }

    function index(Request $request) {
      try {
      if( is_null(auth()->user()) || auth()->user()->is_approved == 0 || auth()->user()->is_admin == 0 ){
        return $this->apiResponse(null,'non authrized ',401, null);
      }  
      $certificates = Certificate::limit(10)->offset($request->offset)->get(); 
      return response()->json($certificates);
    } catch (\Exception $e) {
      Log::error('An error occurred: ' . $e->getMessage());
      return $this->apiResponse(null,'An error occurred',502,null);
  }
    }

    public function store(Request $request)
    {
      try {
     if(is_null(auth()->user())||auth()->user()->is_approved == 0 ){
        return $this->apiResponse(null,'non authrized ',401, null);
     
      }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validate image
            'type_id' => 'required|exists:certificate_types',
            'user_id' => 'required|exists:users'
        ]);
       
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
           // $validator['image'] = $imageName;
        }

        $certificate = Certificate::create([
          'name' => $request->name,
          'image' => $imageName,
          'type_id' => $request->type_id,
          'user_id'=>auth()->user()->id,
          ]);

        return $this->apiResponse($certificate,'created successfully',201, null);
      } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return $this->apiResponse(null,'An error occurred',502,null);
    }
    }


    public function update($id)
    {
      try {
      if( is_null(auth()->user()) || auth()->user()->is_approved == 0  ){
        return $this->apiResponse(null,'you are not authrized for this action',401);
        }else {
            $certificate=Certificate::find($id);  
            $certificate->update($request->all());
            return $this->apiResponse($certificate,'certificate updated',200,null);
        }  
      } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return $this->apiResponse(null,'An error occurred',502,null);
    }
    }



    function destroy($id) {
      try {
      if( is_null(auth()->user()) || auth()->user()->is_approved == 0 || auth()->user()->is_admin == 0 ){
        return $this->apiResponse(null,'non authrized ',401, null);
      } 
      $certificate=Certificate::find($id);
      if($certificate){
        Certificate::destroy($id);
        return $this->apiResponse(null,'deleted',200, null);
      }
    } catch (\Exception $e) {
      Log::error('An error occurred: ' . $e->getMessage());
      return $this->apiResponse(null,'An error occurred',502,null);
  }
    }

    function show($id) {
      try {
      if( is_null(auth()->user()) || auth()->user()->is_approved == 0 || auth()->user()->is_admin == 0 ){
        return $this->apiResponse(null,'non authrized ',401, null);
      } 
      $certificate=Certificate::find($id);
      if($certificate){
      
        return $this->apiResponse(null,'fetched',200, null);
      }
    } catch (\Exception $e) {
      Log::error('An error occurred: ' . $e->getMessage());
      return $this->apiResponse(null,'An error occurred',502,null);
  }
    }

    

}
