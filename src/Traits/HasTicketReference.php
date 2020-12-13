<?php


namespace RexlManu\LaravelTickets\Traits;


trait HasTicketReference
{

    public function toReference() : string
    {
        $type = basename(get_class($this));
        return "$type #$this->getKey()";
    }

}
