<?php


namespace RexlManu\LaravelTickets\Observers;


use Ramsey\Uuid\Uuid;
use RexlManu\LaravelTickets\Events\TicketCloseEvent;
use RexlManu\LaravelTickets\Events\TicketOpenEvent;
use RexlManu\LaravelTickets\Models\Ticket;
use RexlManu\LaravelTickets\Models\TicketActivity;
use RexlManu\LaravelTickets\Models\TicketMessage;

class TicketObserver
{

    public function created(Ticket $ticket)
    {
        $ticketActivity = new TicketActivity([ 'type' => 'CREATE' ]);
        $ticketActivity->ticket()->associate($ticket);
        $ticketActivity->targetable()->associate($ticket);
        $ticketActivity->save();

        event(new TicketOpenEvent($ticket));
    }

    public function updated(Ticket $ticket)
    {
        if ($ticket->wasChanged('state')) {
            if ($ticket->state === 'CLOSED') {
                event(new TicketCloseEvent($ticket));
            }
            if ($ticket->state == 'ANSWERED') {
                return;
            }
            /**
             * EDIT BY RENE, bin einfach krasser als Manu :O
             */
            $ticketActivity = new TicketActivity([ 'type' => $ticket->state == 'OPEN' ? 'OPEN' : 'CLOSE' ]);
            $ticketActivity->ticket()->associate($ticket);
            $ticketActivity->targetable()->associate($ticket);
            $ticketActivity->save();
        }
    }

    public function deleting(Ticket $ticket)
    {
        $ticket->messages()->get()->each(fn(TicketMessage $ticketMessage) => $ticketMessage->delete());
    }

    public function creating(Ticket $ticket)
    {
        if (config('laravel-tickets.models.uuid') && empty($model->id)) {
            $ticket->id = Uuid::uuid4();
        }
    }

}
