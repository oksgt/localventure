<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketOrderDetail extends Model
{
    use HasFactory, SoftDeletes; // ✅ Enables Factory & Soft Delete support

    protected $table = 'ticket_order_details'; // ✅ Explicitly sets table name

    protected $fillable = [
        'ticket_code',
        'order_id', 'guest_type_id', 'day_type', 'visit_date',
        'insurance_price', 'base_price', 'total_price', 'qty',
        'created_by', 'updated_by', 'deleted_by'
    ]; // ✅ Allows mass assignment

    protected $dates = ['visit_date', 'created_at', 'updated_at', 'deleted_at']; // ✅ Ensures proper date casting

    // ✅ Relationships
    public function ticketOrder()
    {
        return $this->belongsTo(TicketOrder::class, 'order_id');
    }

    public function guestType()
    {
        return $this->belongsTo(GuestType::class, 'guest_type_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
