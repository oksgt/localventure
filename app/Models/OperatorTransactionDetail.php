<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorTransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'operator_transaction_detail';

    protected $fillable = [
        'operator_transaction_id',
        'ticket_order_id',
        'qty',
        'amount',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Relationship: Each detail belongs to an OperatorTransaction
     */
    public function transaction()
    {
        return $this->belongsTo(OperatorTransaction::class, 'operator_transaction_id');
    }

    /**
     * Relationship: Each detail is linked to a TicketOrder
     */
    public function ticketOrder()
    {
        return $this->belongsTo(TicketOrder::class, 'ticket_order_id');
    }
}
