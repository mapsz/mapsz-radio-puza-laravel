<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $inputs = [
        [
            'name' => 'username',
            'caption' => 'Username',
            'required'=>true,
        ],
        [
            'name' => 'password',
            'caption' => 'Password',
            'type' => 'password',
            'required'=>true,
        ],
        [
            'name' => 'name',
            'caption' => 'Name',
            'required'=>true,
        ],
        [
            'name' => 'email',
            'caption' => 'Email',
            'type' => 'email',
        ],
        [
            'name' => 'color',
            'caption' => 'Color',
            'type' => 'color',
        ],
        [
            'name' => 'player',
            'caption' => 'Player',
            'type' => 'text',
        ],
        [
            'name' => 'form',
            'caption' => 'Form',
            'type' => 'text',
        ],
        [
            'name' => 'expire',
            'caption' => 'Expire',
            'type' => 'datetime-local',
        ],
        [
            'name' => 'image',
            'caption' => 'Image',
            'type' => 'file',
        ],
    ];
    public function jugeGetInputs(){return $this->inputs;}
    public function jugeGetPostInputs(){return $this->inputs;}
    public function jugeGetKeys(){
        return [
            ['key'    => 'id','label' => 'ID'],
            ['key'    => 'username','label' => 'Username'],
            ['key'    => 'name','label' => 'Name'],
            ['key'    => 'email','label' => 'Email'],
            ['key'    => 'color','label' => 'Color', 'type' => 'color'],
            ['key'    => 'player','label' => 'Player'],
            ['key'    => 'form','label' => 'Form'],
            ['key'    => 'expire','label' => 'expire', 'type' => 'moment', 'moment' => 'lll'],
            ['key'    => 'image','label' => 'Image', 'type' => 'image'],
            ['key'    => 'loginAsUser','label' => 'login',  'type' => 'custom', 'component' => 'userLoginAsUser'],
        ];
    }

    public static function jugeGet($request = []) {
        //Model
        $query = new self;

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

        {//After query work
          //
        }

        //Return
        return $data;
      }



}
