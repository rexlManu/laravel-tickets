<?php


namespace RexlManu\LaravelTickets\Interfaces;


interface TicketReference
{

    /**
     * Check if a user has access to this reference
     *
     * @return boolean
     */
    function hasReferenceAccess() : bool;

    /**
     * Show the name when on selection
     *
     * @return string
     */
    function toReference() : string;

}
