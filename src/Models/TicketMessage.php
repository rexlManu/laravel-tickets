<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{

    protected $fillable = [
        'message'
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(config('laravel-tickets.user'));
    }

}
