<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

/**
 * Class TicketActivity
 *
 * This data model is used to log every action that was fired by a interaction
 *
 * @package RexlManu\LaravelTickets\Models
 */
class TicketActivity extends Model
{
    use HasConfigModel;

    protected $fillable = [
        'type'
    ];

    /**
     * Gives the ticket that the activity belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function getTable()
    {
        return config('laravel-tickets.database.ticket-activities-table');
    }

    /**
     * Gives the target, can be ticket, user or message
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function targetable()
    {
        return $this->morphTo();
    }
}
