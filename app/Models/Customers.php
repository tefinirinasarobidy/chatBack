<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customers extends Model
{
    use HasFactory,Uuids,Notifiable;

    protected $guarded = [];
}
