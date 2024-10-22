<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DeleteAccountRequests extends Model
{
    use HasFactory;
    protected $table = 'delete_account_requests';
    protected $fillable = ['email'];

}