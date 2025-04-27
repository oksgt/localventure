<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMapping extends Model
{
    protected $table = 'user_mapping';

    protected $fillable = [
        'user_id',
        'destination_id',
        'role_id',
    ];
}
