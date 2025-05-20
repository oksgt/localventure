<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'operator_transaction';

    protected $fillable = [
        'billing_number',
        'destination_id',
        'total_ticket_order',
        'total_amount',
        'transfer_receipt',
        'transfer_amount',
        'transfer_date',
        'transfer_approval',
        'transfer_approval_date',
        'transfer_approval_user',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Relationship: OperatorTransaction has many OperatorTransactionDetails
     */
    public function details()
    {
        return $this->hasMany(OperatorTransactionDetail::class, 'operator_transaction_id');
    }
}
