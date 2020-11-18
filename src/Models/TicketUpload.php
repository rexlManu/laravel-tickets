<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

class TicketUpload extends Model
{

    use HasConfigModel;

    protected $fillable = [
        'path'
    ];

    public function getTable()
    {
        return config('laravel-tickets.database.ticket-uploads-table');
    }

    public function message()
    {
        return $this->belongsTo(TicketMessage::class, 'ticket_message_id');
    }

}
