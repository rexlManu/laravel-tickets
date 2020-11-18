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
     * Combine type and id to a string for forms
     *
     * @return string
     */
    function toReference() : string;

}
