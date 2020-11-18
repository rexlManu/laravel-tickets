<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

class TicketReference extends Model
{

    use HasConfigModel;

    public function getTable()
    {
        return config('laravel-tickets.database.ticket-references-table');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function referenceable()
    {
        return $this->morphTo();
    }

}
