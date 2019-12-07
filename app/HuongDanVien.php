<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HuongDanVien extends Model
{
    protected $table = 'huongdanvien';
    protected $fillable = ['name', 'img', 'type_card', 'card_number', 'expiry_date', 'issue_place', 'experience_util_now'];
    public $timestamps = false;
}
