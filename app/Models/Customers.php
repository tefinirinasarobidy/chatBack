<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens as PassportHasApiTokens;
use Laravel\Sanctum\HasApiTokens;

class Customers extends Model
{
    use HasFactory,Uuids,Notifiable,PassportHasApiTokens;

    protected $guarded = [];

}
