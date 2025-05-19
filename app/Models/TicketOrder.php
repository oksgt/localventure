<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketOrder extends Model
{
    use HasFactory, SoftDeletes; // ✅ Enables Factory & Soft Delete support

    protected $table = 'ticket_orders'; // ✅ Explicitly sets table name

    protected $fillable = [
        'destination_id', 'visitor_type', 'visit_date', 'visitor_name',
        'visitor_address', 'visitor_phone', 'visitor_origin_description',
        'visitor_email', 'visitor_age', 'total_visitor', 'total_price',
        'billing_number', 'payment_status', 'purchasing_type', 'notes',
        'created_by', 'updated_by', 'deleted_by', 'id_kecamatan',
        'id_kabupaten', 'id_provinsi', 'visitor_male_count',
        'visitor_female_count', 'payment_type_id', 'bank_id'
    ]; // ✅ Allows mass assignment

    protected $dates = ['visit_date', 'created_at', 'updated_at', 'deleted_at']; // ✅ Ensures proper date casting

    // ✅ Relationships
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
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

    public function details()
    {
        return $this->hasMany(TicketOrderDetail::class, 'order_id');
    }

    public function groupedDetails()
    {
        return $this->hasMany(TicketOrderDetail::class, 'order_id')
            ->selectRaw('order_id, guest_types.name as guest_type_name, guest_type_id, SUM(qty) as total_qty, SUM(total_price) as total_price')
            ->join('guest_types', 'guest_types.id', '=', 'ticket_order_details.guest_type_id') // ✅ Ensure correct table reference
            ->groupBy('order_id', 'guest_type_id', 'guest_types.name');
    }
}
