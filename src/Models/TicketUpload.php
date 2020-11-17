<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;

class TicketUpload extends Model
{

    protected $fillable = [
        'path'
    ];

    public function message()
    {
        return $this->belongsTo(TicketMessage::class, 'ticket_message_id');
    }

}
