<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'destinations';

    protected $fillable = [
        'name',
        'description',
        'address',
        'latlon',
        'available',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Relationships
     */

    // Created by relationship
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Updated by relationship
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Deleted by relationship
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'user_mapping', 'destination_id', 'user_id');
    }

    public function pricing()
    {
        return $this->hasMany(Pricing::class);
    }

    public function images()
    {
        return $this->hasMany(DestinationGallery::class, 'destination_id');
    }
}
