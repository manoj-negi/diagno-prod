<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $primarykey = "id";
    protected $table = "faqs";

    protected $fillable = [
        'user_id',
        'question',
        'spenish_question',
        'answer',
        'spenish_answer',
        'status'
    ];
    public function roles(){
        return $this->hasOne(Role::class,'id','user_id');
    }
   
}