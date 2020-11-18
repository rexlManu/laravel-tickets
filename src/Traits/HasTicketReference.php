<?php


namespace RexlManu\LaravelTickets\Traits;


trait HasTicketReference
{

    public function toReference() : string
    {
        $type = get_class($this);
        return "$type,$this->id";
    }

}
