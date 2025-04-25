<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Destination extends Model
{
    use HasFactory, SoftDeletes, SpatialTrait;

    protected $table = 'destinations';

    protected $fillable = [
        'name',
        'description',
        'address',
        'is_publish',
        'available',
        'latlon',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $spatialFields = ['latlon']; // Enable spatial field handling

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
}
