<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinationGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'destination_gallery';

    protected $fillable = [
        'destination_id',
        'original_file_name',
        'filename',
        'file_ext',
        'file_size',
        'is_cover',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Relationships
     */

    // Destination relationship
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

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

    /**
     * Get full image URL.
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/destination/' . $this->filename);
    }
}
