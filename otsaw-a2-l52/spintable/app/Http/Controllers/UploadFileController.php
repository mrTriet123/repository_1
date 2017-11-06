<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Session;
use Validator;
use Storage;
use Aws\S3\S3Client;
use App\Document;
use App\CustomerDocument;
use Helpers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UploadFileController;

class UploadFileController extends Controller
{

	public static function saveS3($folder_name, $file_name, $file_type, $drive_id)
    {
        // $bucket = 'fivmoon-test';
        $document = new Document;
        $file = $file_name->getClientOriginalName();
        $disk = Storage::disk('s3');
       
        $documentUrl = $disk->getDriver()->getAdapter()->getClient()->getObjectUrl(env('S3_BUCKET'), $folder_name);
        $documentFileName = time() . '.' . $file_name->getClientOriginalExtension();
        $filePath = "/{$folder_name}/". $documentFileName;
        $disk->put($filePath, file_get_contents($file_name));

        if($disk->put($filePath, file_get_contents($file_name)))
        {
            $document->driver_id = $drive_id;
            $document->name = $documentFileName;
            $document->path = $documentUrl . '/' . $documentFileName;
            $document->file_type = $file_type;
            $document->name_origin = $file;

            // Save to database
            if( $document->save() )
            {
                return array('image_url' => $documentUrl . '/' . $documentFileName ,'image_id' => $document->id);
            }
        }
        return 'Error saving document!';
    }

    public static function saveCustomerDocument($folder_name, $file_name, $file_type, $customer_id)
    {
        // $bucket = 'fivmoon-test';
        $document = new CustomerDocument;
        $file = $file_name->getClientOriginalName();
        $disk = Storage::disk('s3');
       
        $documentUrl = $disk->getDriver()->getAdapter()->getClient()->getObjectUrl(env('S3_BUCKET'), $folder_name);
        $documentFileName = time() . '.' . $file_name->getClientOriginalExtension();
        $filePath = "/{$folder_name}/". $documentFileName;
        $disk->put($filePath, file_get_contents($file_name));

        if($disk->put($filePath, file_get_contents($file_name)))
        {
            $document->customer_id = $customer_id;
            $document->name = $documentFileName;
            $document->path = $documentUrl . '/' . $documentFileName;
            $document->file_type = $file_type;
            $document->name_origin = $file;

            // Save to database
            if( $document->save() )
            {
                return array('image_url' => $documentUrl . '/' . $documentFileName ,'image_id' => $document->id);
            }
        }
        return 'Error saving document!';
    }



    public static function saveJobAudio($file)
    {	//user_id, audio file, time
    	// $bucket = 'fivmoon-test';
        if($file != null)
        {
           $folder_name = 'job_audios';
            $disk = Storage::disk('s3');
            $documentUrl = $disk->getDriver()->getAdapter()->getClient()->getObjectUrl(env('S3_BUCKET'), $folder_name);
            $documentFileName = $file->getClientOriginalName();
            // $documentFileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = "/{$folder_name}/". $documentFileName;
            $disk->put($filePath, file_get_contents($file));
            if($disk->put($filePath, file_get_contents($file)))
            {
                $path = $documentUrl . '/' . $documentFileName;
                $result = array('path' => $path);
                // $saveData = array(
                //  'driver_id' => 1,
                //  'job_id' => 2,
                //  'path' =>$documentUrl . '/' . $documentFileName
                //  );
                return $result;
            } 
        }
    	
        return 'Error saving document!';
    }

    public static function saveJobPhoto($file)
    {
    	$folder_name = 'job_photos';
    	$disk = Storage::disk('s3');
        $documentUrl = $disk->getDriver()->getAdapter()->getClient()->getObjectUrl(env('S3_BUCKET'), $folder_name);
        $documentFileName = $file->getClientOriginalName();
        $filePath = "/{$folder_name}/". $documentFileName;
        $disk->put($filePath, file_get_contents($file));

        if($disk->put($filePath, file_get_contents($file)))
        {
        	$saveData = array(
        		'path' =>$documentUrl . '/' . $documentFileName
        		);
            return $saveData;
        }
        return 'Error saving document!';
    }

    public static function saveSubCustomerPhoto($file)
    {   
        $folder_name = 'profile_images';
        $disk = Storage::disk('s3');
        $file_name = $file->getClientOriginalName();
        $documentUrl = $disk->getDriver()->getAdapter()->getClient()->getObjectUrl(env('S3_BUCKET'), $folder_name);
        $documentFileName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = "/{$folder_name}/". $documentFileName;
        $disk->put($filePath, file_get_contents($file));

        if($disk->put($filePath, file_get_contents($file)))
        {
            $saveData = array(
                'name_origin' => $file_name,
                'path' => $documentUrl . '/' . $documentFileName,
                'name' => $documentFileName
                );
            // Save to database
            return $saveData;
        }
        return 'Error saving document!';
    }


    public static function deleteS3($folder_name, $file_name)
    {
        $disk = Storage::disk('s3');
        $disk->delete($folder_name.'/'.$file_name);
    }


}