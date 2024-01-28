<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugeModel extends Model
{

  public function jugeGetInputs(){return $this->inputs;} 
  public function jugeGetPostInputs(){return $this->inputs;} 
  public function jugeGetKeys(){return $this->keys;} 

  public static function jugeGet($request = []) {

    //Model
    $query = new static();
  
    {//With
      //
    }
  
    {//Where
      $query = JugeCRUD::whereSearches($query,$request);
    }
  
    //Order by
    $query = $query->OrderBy('created_at', 'DESC');
  
    //Get
    $data = JugeCRUD::get($query,$request);
  
    //Single
    if(isset($request['single']) && isset($request['id']) && isset($data[0])){
      $data = [];
      if(isset($data[0])) $data = $data[0];
    }
  
    //Return
    return $data;
  }

}
