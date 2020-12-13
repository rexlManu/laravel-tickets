<?php


namespace RexlManu\LaravelTickets\Traits;

/**
 * Trait HasTicketReference
 *
 * For ticket references, when used on a model,
 * the model also needs to be registered in the configuration
 *
 * @package RexlManu\LaravelTickets\Traits
 */
trait HasTicketReference
{

    public function toReference() : string
    {
        $type = basename(get_class($this));
        return "$type #$this->getKey()";
    }

}
