<?php

namespace App\Http\Controllers\Caregiver\Document;

use App\Http\Controllers\Controller;
use App\Models\CaregiverStatusInformation;
use App\Models\ChildAbuseDocument;
use App\Models\CovidDocument;
use App\Models\CriminalDocument;
use App\Models\DrivingDocument;
use App\Models\EmploymentEligibilityDocument;
use App\Models\IdentificationDocument;
use App\Models\TuberculosisDocument;
use App\Models\User;
use App\Models\W4Document;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentUploadController extends Controller
{
    use ApiResponse;

    public function getDocument(){

        $details = User::where('id', auth('sanctum')->user()->id)->with('covid','childAbuse','criminal','driving','employment','identification','tuberculosis','w4_form')->first();
        return $this->success('Document fetched successfully.',  $details, 'null', 200);
    }
    public function uploadDocument(Request $request){
        $validator = Validator::make($request->all(),[
            'documentCategory' => 'required',
            'document' => 'required|mimes:jpg,png,jpeg,pdf|max:2048'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Failed To Upload Document. '.$validator->errors()->first(), null, null, 500);
        }else{
            try{
                $documentCategory = $request->documentCategory;
                $extension = $request->file('document')->extension();
                $document = $request->document;
    
                // Upload file to folder
                $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                $document->move(public_path('Caregiver/Uploads/Documents/'), $new_name);
                $file = 'Caregiver/Uploads/Documents/' . $new_name;
    
                $type = '';
    
                if(($extension == 'png') || ($extension == 'jpg') || ($extension == 'jpeg')){
                    $type = 'image';
                }else{
                    $type = 'pdf';
                }
    
                if($documentCategory == 'covid'){
                    CovidDocument::create([
                        'type' => $type,
                        'name' => $document->getClientOriginalName(),
                        'image' => $file,
                        'user_id' => auth('sanctum')->user()->id,
                        'expiry_date' => $request->expiry_date
                    ]);
                }else if($documentCategory == 'childAbuse'){
                    ChildAbuseDocument::create([
                        'type' => $type,
                        'name' => $document->getClientOriginalName(),
                        'image' => $file,
                        'user_id' => auth('sanctum')->user()->id,
                        'expiry_date' => $request->expiry_date
                    ]);
                }else if($documentCategory == 'criminal'){
                    CriminalDocument::create([
                        'type' => $type,
                        'name' => $document->getClientOriginalName(),
                        'image' => $file,
                        'user_id' => auth('sanctum')->user()->id,
                        'expiry_date' => $request->expiry_date
                    ]);
                }else if($documentCategory == 'driving'){
                    DrivingDocument::create([
                        'type' => $type,
                        'name' => $document->getClientOriginalName(),
                        'image' => $file,
                        'user_id' => auth('sanctum')->user()->id,
                        'expiry_date' => $request->expiry_date
                    ]);
                }else if($documentCategory == 'employment'){
                    EmploymentEligibilityDocument::create([
                        'type' => $type,
                        'name' => $document->getClientOriginalName(),
                        'image' => $file,
                        'user_id' => auth('sanctum')->user()->id,
                        'expiry_date' => $request->expiry_date
                    ]);
                }else if($documentCategory == 'identification'){
                    IdentificationDocument::create([
                        'type' => $type,
                        'name' => $document->getClientOriginalName(),
                        'image' => $file,
                        'user_id' => auth('sanctum')->user()->id,
                        'expiry_date' => $request->expiry_date
                    ]);
                }else if($documentCategory == 'tuberculosis'){
                    TuberculosisDocument::create([
                        'type' => $type,
                        'name' => $document->getClientOriginalName(),
                        'image' => $file,
                        'user_id' => auth('sanctum')->user()->id,
                        'expiry_date' => $request->expiry_date
                    ]);
                }else if($documentCategory == 'w4_form'){
                    W4Document::create([
                        'type' => $type,
                        'name' => $document->getClientOriginalName(),
                        'image' => $file,
                        'user_id' => auth('sanctum')->user()->id,
                        'expiry_date' => $request->expiry_date
                    ]);
                }else {
                    return $this->error('Oops! Documents upload failed', null, 'null', 400);
                }
                
                $details = User::where('id', Auth::user()->id)->with('covid','childAbuse','criminal','driving','employment','identification','tuberculosis','w4_form')->first();
                
                return $this->success('Great! Document Uploaded successfully.',  $details, 'null', 201);

            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong. Failed To Upload Document.', null, null, 500);
            }
        }
    }

    public function deleteDocument(Request $request){
        
        $validator = Validator::make($request->all(),[
            'documentCategory' => 'required',
            'document_id' => 'required'
        ]);
        if($validator->fails()){
            return $this->error('Oops! Failed To Delete Document. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                if($request->documentCategory == 'covid'){
                    CovidDocument::where('id', $request->document_id)->where('user_id', Auth::user()->id)->update([
                        'status' => 0
                    ]);
                }else if($request->documentCategory == 'childAbuse'){
                    ChildAbuseDocument::where('id', $request->document_id)->where('user_id', Auth::user()->id)->update([
                        'status' => 0
                    ]);
                }else if($request->documentCategory == 'criminal'){
                    CriminalDocument::where('id', $request->document_id)->where('user_id', Auth::user()->id)->update([
                        'status' => 0
                    ]);
                }else if($request->documentCategory == 'driving'){
                    DrivingDocument::where('id', $request->document_id)->where('user_id', Auth::user()->id)->update([
                        'status' => 0
                    ]);
                }else if($request->documentCategory == 'employment'){
                    EmploymentEligibilityDocument::where('id', $request->document_id)->where('user_id', Auth::user()->id)->update([
                        'status' => 0
                    ]);
                }else if($request->documentCategory == 'identification'){
                    IdentificationDocument::where('id', $request->document_id)->where('user_id', Auth::user()->id)->update([
                        'status' => 0
                    ]);
                }else if($request->documentCategory == 'tuberculosis'){
                    TuberculosisDocument::where('id', $request->document_id)->where('user_id', Auth::user()->id)->update([
                        'status' => 0
                    ]);
                }else if($request->documentCategory == 'w4_form'){
                    W4Document::where('id', $request->document_id)->where('user_id', Auth::user()->id)->update([
                        'status' => 0
                    ]);
                }else {
                    return $this->error('Oops! Failed To Delete Document', null, 'null', 400);
                }

                return $this->success('Great! Document Deleted successfully.',  null, 'null', 201);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
        
    }

    public function updateStatus(Request $request){
        try{
            CaregiverStatusInformation::where('user_id', Auth::user()->id)->update([
                'is_document_uploaded' => 1
            ]);
            return $this->success('Great! Status Updated successfully.',  null, 'null', 201);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
    }
}
