<?php

namespace App\Http\Controllers;
include 'C:\Users\maito\OneDrive\Desktop\amazone\S3-Amazone\vendor\autoload.php';
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

class S3Controller extends Controller
{
    public function UploadFile(Request $request){
        $bucket_name = $request->input('BucketName'); 
        $region = $request->input('Region'); 
        $input_key = $request->input('Key');  
        $secret = $request->input('Secret'); 
        $file_Path =  $request->input('FilePath');
        $bucketName = $bucket_name;
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => $region ,
            'credentials' => [
                'key'    => $input_key,
                'secret' => $secret  ,                          
            ]
        ]);           
        $filePath = $file_Path;
        $binary = file_get_contents($filePath);      
        $key = basename($filePath);             
        try {
             $result = $s3->putObject([
                 'Bucket' => $bucketName,
                 'Key'    => $key,
                 'contentType' => 'image/jpeg/mp4',
                 'Body'   =>  $binary,
                 'ACL'    => 'public-read',
             ]);
             return  $result->get('ObjectURL');
         } catch (Aws\S3\Exception\S3Exception $e) {
             return "There was an error uploading the file.\n";
             return $e->getMessage();
         }
    }
}
