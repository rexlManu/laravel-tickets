<?php


namespace RexlManu\LaravelTickets\Observers;


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
    }

    public function updated(Ticket $ticket)
    {
        if ($ticket->wasChanged('type')) {
            if ($ticket->type == 'ANSWERED') {
                return;
            }
            $ticketActivity = new TicketActivity([ 'type' => $ticket->type == 'OPEN' ?? 'CLOSED' ]);
            $ticketActivity->ticket()->associate($ticket);
            $ticketActivity->targetable()->associate($ticket);
            $ticketActivity->save();
        }
    }

    public function deleting(Ticket $ticket)
    {
        $ticket->messages()->get()->each(fn(TicketMessage $ticketMessage) => $ticketMessage->delete());
    }

}
