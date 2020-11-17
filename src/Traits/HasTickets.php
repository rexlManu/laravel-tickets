<?php


namespace RexlManu\LaravelTickets\Traits;


use Illuminate\Database\Eloquent\Relations\HasMany;
use RexlManu\LaravelTickets\Models\Ticket;

trait HasTickets
{

    /**
     * Gives every ticket that belongs to user
     *
     * @return HasMany
     */
    function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
