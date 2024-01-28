<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

use App\Models\JugeFileUpload;

class JugeCRUD extends Model
{
  use HasFactory;

  public $table = 'juge_cruds';

  public static function setFiles($row){
    
    {//Get files key
      $fileKeys = [];
      foreach ($row->jugeGetKeys() as $i => $key) {
        if(isset($key['type'])){
          if($key['type'] == 'image'){
            $fileKeys[$key['key']] = null;
          }
        }
      }
    }

    //Get class name
    $className = strtolower(substr(get_class($row), strrpos(get_class($row), '\\')+1));

    //Set Files to row
    foreach ($fileKeys as $key => $value) {

      //Set path
      $path = public_path() . '/'. $className . '/' . $key;

      //Get files
      $files = scandir($path);
      $rFiles = [];
      foreach ($files as $file) {
        if(strpos($file, $row->id . '_') === 0){
          array_push($rFiles, '/'. $className . '/' . $key . '/' . $file);
        }      
      }

      $row->$key = $rFiles;

    }

    return $row;

  }
  
  public static function get($query,$request = []){
    //Pagginate limit
    if(isset($request['limit']) && $request['limit']){
      $limit = $request['limit'];
    }else{
      $limit = 100;
    }

    //Get
    if(!isset($request['page'])){
      $data = $query->get();
    }else{
      $data = $query->paginate($limit);
    }
    
    //Files
    if(!isset($request['no_files'])){
      foreach ($data as $row) {
        $row = self::setFiles($row);
      }
    }   

    //Single
    if(isset($request['single']) || isset($request['id'])){
      if(isset($data[0])) $data = $data[0];
    }

    //Test
    if(isset($request['test'])){
      dd($data->toArray());
      dd($data);
    }


    return $data;
  }

  public static function setMetas($data){
    foreach ($data as $row) {
      //Metas
      if(isset($row['metas'])){
        foreach ($row['metas'] as $meta) {
          $row[$meta->name] = $meta->value;
        }
        unset($row['metas']);
      }
      //Long Metas
      if(isset($row['longMetas'])){
        foreach ($row['longMetas'] as $row) {
          $row[$meta->name] = $meta->value;
        }
        unset($row['longMetas']);
      }
    }
    return $data;
  }

  public static function whereSearches($query,$request){
    
    if(isset($request['id'])) $query = $query->where('id', $request['id']);
    if(isset($request['search'])){
      $search =\json_decode($request['search']);
      foreach ($search as $key => $value) {
        $query = $query->where($key, $value);
      }
    }
    return $query;
  }

  public static function autoPut($model, $data){
    
    {//Set Data Type
      //Get inputs
      $modelInputs = $model->jugeGetInputs();

      $files = [];
      $db = [];
      foreach ($data as $k => $v) {
  
        //Id
        if($k == 'id') continue;
  
        //Files
        foreach ($modelInputs as $input) {
          if($input['name'] == $k){
            if(!isset($input['type'])) $input['type'] = "text";
            if($input['type'] == 'file'){
              $files[$k] = $v;
              continue 2;
            }
          }
        }
  
        //DB
        $db[$k] = $v;
      }
    }

    {//Setup model to put
      foreach ($db as $k => $v) {
        $model->$k = $v;
      }
    }   

    try{
      //Start DB
      DB::beginTransaction();
      
      //Save DB
      $save = $model->save();
      
      {//Save Files

        $id = $model->id;

        {//get class name
          $arr = explode('\\',get_class($model));
          $modelName = strtolower ($arr[count($arr)-1]);
        }               

        //Save
        foreach ($files as $k => $fs) {
          //Make path
          $path = public_path() . '/' . $modelName . '/' . $k . '/';

          
          foreach ($fs as $file) {
            //Make name
            $fileName = JugeFileUpload::generateFileName($path, $id);

            //Save
            if(!JugeFileUpload::saveFile($file, $path.$fileName)) return false;
          }
          
        }
      }      
      
      //Store to DB
      DB::commit();
    } catch (Exception $e){
      // Rollback from DB
      DB::rollback();
    }

    if($save) return $model->jugeGet(['id' => $model->id]);

    return false;
  }

  public static function autoPost($model, $data){

    //Get model to edit
    $model = $model->where('id', $data['id'])->first();

    {//Set Data Type
      //Get inputs
      $modelInputs = $model->jugeGetInputs();

      $files = [];
      $db = [];
      foreach ($data as $k => $v) {
  
        //Id
        if($k == 'id') continue;
  
        //Files
        foreach ($modelInputs as $input) {
          if($input['name'] == $k){
            if(!isset($input['type'])) $input['type'] = "text";
            if($input['type'] == 'file'){
              $files[$k] = $v;
              continue 2;
            }
          }
        }
  
        //DB
        $db[$k] = $v;
      }
    }   

    {//Setup model to edit
      foreach ($db as $k => $v) {
        if($k == 'id') continue;
        $model->$k = $v;
      }
    }

    {//Check images count
      foreach ($files as $key => $file) {
        $k = array_search($key, array_column($modelInputs, 'name'));
        if($k !== false && is_array($file)){          
          //Set files
          $model = self::setFiles($model);
          //Get got files
          $gotFiles = count($model[$key]);
          unset($model[$key]);
          //Get max files
          $fileMax = isset($modelInputs[$k]['fileMax']) ? $modelInputs[$k]['fileMax'] : 1;
          if($fileMax < ($gotFiles + count($file)) ){
            Validator::make(['code' => false], ['code' => 'required|accepted'], ['code.accepted' => "Maximum $fileMax $key allowed"])->validate();
          }
        }
      }

    }
    
    {//Save
      try{
        //Start DB
        DB::beginTransaction();
        
        //Save DB
        $save = $model->save();
        
        {//Save Files
  
          $id = $model->id;
  
          {//get class name
            $arr = explode('\\',get_class($model));
            $modelName = strtolower ($arr[count($arr)-1]);
          }               
  
          //Save
          foreach ($files as $k => $fs) {
            //Make path
            $path = public_path() . '/' . $modelName . '/' . $k . '/';
  
            
            foreach ($fs as $file) {
              //Make name
              $fileName = JugeFileUpload::generateFileName($path, $id);
  
              //Save
              if(!JugeFileUpload::saveFile($file, $path.$fileName)) return false;
            }
            
          }
        }      
        
        //Store to DB
        DB::commit();
      } catch (Exception $e){
        // Rollback from DB
        DB::rollback();
      }  
    }

    //Get saved model    
    if($save) return $model->jugeGet(['id' => $data['id']]);

    return false;
  }

  public static function autoDelete($model, $id){
    return ($model::find($id))->delete();
  }

}
