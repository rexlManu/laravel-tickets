<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

class TicketMessage extends Model
{

    use HasConfigModel;

    protected $fillable = [
        'message'
    ];

    public function getTable()
    {
        return config('laravel-tickets.database.ticket-messages-table');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(config('laravel-tickets.user'));
    }

    public function uploads()
    {
        return $this->hasMany(TicketUpload::class);
    }

}
