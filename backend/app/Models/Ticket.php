<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'user_id',
        'destination_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'visit_date',
        'quantity',
        'price_per_ticket',
        'total_price',
        'payment_method',
        'status',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
