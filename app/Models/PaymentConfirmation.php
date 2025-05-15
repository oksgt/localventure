<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConfirmation extends Model
{
    use HasFactory;

    protected $table = 'payment_confirmation';
    protected $fillable = [
        'ticket_order_id', 'billing_number', 'transfer_amount',
        'bank_name', 'account_name', 'account_number', 'image'
    ];
}
