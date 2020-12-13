<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

/**
 * Class TicketUpload
 *
 * When a user sent a message with a file, the file gets attached to the ticket message
 *
 * @package RexlManu\LaravelTickets\Models
 */
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

    /**
     * The message where the upload is attached to it
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(TicketMessage::class, 'ticket_message_id');
    }

}
