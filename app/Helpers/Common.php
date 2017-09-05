<?php
namespace App\Helpers;
use App\Fileentry; 
use App\Report; 
use Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class Common{
   
    public function __construct(){
       
    }

    function setErrorMessages($errors = NULL) {

        if (!empty($errors)):

            $error = [];

            foreach ($errors as $key => $value) {

                foreach ($value as $v) {

                    $error[] = $v;
                }
            }

            return $error;

        endif;
    }

   public static function pr($data) {
        if(is_array($data) || is_object($data)){
            echo "<pre>";
            print_r($data);
            die;
        }else{
            echo $data;
            die;
        }
           
    }
    
    public static function vd($data) {
        if(is_array($data) || is_object($data)){
            echo "<pre>";
            var_dump($data);die;
        }else{
            echo $data;die;
        }
           
    }
    // get all stored files
    public static function get_all_files() {
        /* file storage start */         
         $fileentry = new Fileentry();
         return $fileentry->get()->toArray();
         
         /* file storage end */
    }
    
    // download file
    public static function download($fileId){   
        $entry = Fileentry::where('file_id', '=', $fileId)->firstOrFail();
        $pathToFile=storage_path()."/app/".$entry->filename;
        return response()->download($pathToFile);           
    }
    
    // download file
    public static function downloadReport($fileId){ 
        $entry = Report::where('id', '=', $fileId)->firstOrFail();
        $pathToFile=storage_path()."/pdf/".$entry->file_name;
        return response()->download($pathToFile);           
    }
    
}
?>