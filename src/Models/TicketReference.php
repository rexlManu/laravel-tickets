<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

/**
 * Class TicketReference
 *
 * Used to reference a specific model to a ticket
 *
 * @package RexlManu\LaravelTickets\Models
 */
class TicketReference extends Model
{

    use HasConfigModel;

    public function getTable()
    {
        return config('laravel-tickets.database.ticket-references-table');
    }

    /**
     * Gives the ticket that belongs to it
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Gives the model that is declared as reference
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function referenceable()
    {
        return $this->morphTo();
    }

}
