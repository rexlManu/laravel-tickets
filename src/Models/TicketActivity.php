<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

class TicketActivity extends Model
{
    use HasConfigModel;

    protected $fillable = [
        'type'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function getTable()
    {
        return config('laravel-tickets.database.ticket-activities-table');
    }

    public function targetable()
    {
        return $this->morphTo();
    }
}
