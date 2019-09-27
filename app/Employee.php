<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
     // 主キーの指定
     protected $primaryKey = 'shain_cd';
     // 主キーが数値型ではない場合
    protected $keyType = 'string';
}
