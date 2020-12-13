<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

/**
 * Class TicketMessage
 *
 * The message that a user sent
 *
 * @package RexlManu\LaravelTickets\Models
 */
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

    /**
     * Gives the ticket that belongs to it
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Gives the creator of the message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('laravel-tickets.user'));
    }

    /**
     * Gives all uploads that a made with the message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function uploads()
    {
        return $this->hasMany(TicketUpload::class);
    }

}
