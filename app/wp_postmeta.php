<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wp_postmeta extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'wp_postmeta';
    protected $fillable =['meta_id', 'post_id', 'meta_key', 'meta_value'];
}
