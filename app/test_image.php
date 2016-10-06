<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class test_image extends Model
{
    protected $table = 'testimage';
    protected $primaryKey = 'testimage_id';
    protected $fillable = ['testimage_name', 'testimage_image'];
}
