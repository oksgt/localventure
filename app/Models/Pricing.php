<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pricing extends Model
{
    use SoftDeletes;

    protected $table = 'pricing';

    protected $fillable = [
        'destination_id',
        'guest_type_id',
        'day_type',
        'base_price',
        'insurance_price',
        'final_price',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function guestType()
    {
        return $this->belongsTo(GuestType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
