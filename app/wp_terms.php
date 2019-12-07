<?php
/**
 * Created by PhpStorm.
 * User: hacke
 * Date: 18/11/2019
 * Time: 11:24 CH
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class wp_terms  extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'wp_terms';

}