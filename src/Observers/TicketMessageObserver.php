<?php


namespace RexlManu\LaravelTickets\Observers;


use RexlManu\LaravelTickets\Events\TicketMessageEvent;
use RexlManu\LaravelTickets\Models\TicketActivity;
use RexlManu\LaravelTickets\Models\TicketMessage;
use RexlManu\LaravelTickets\Models\TicketUpload;
use Storage;

class TicketMessageObserver
{

    public function created(TicketMessage $ticketMessage)
    {
        $ticketActivity = new TicketActivity([ 'type' => 'ANSWER' ]);
        $ticketActivity->ticket()->associate($ticketMessage->ticket()->first());
        $ticketActivity->targetable()->associate($ticketMessage);
        $ticketActivity->save();

        $ticket = $ticketMessage->ticket;

        if ($ticketMessage->user_id != $ticket->user_id) {
            $ticket->update([ 'state' => 'ANSWERED' ]);
        }

        event(new TicketMessageEvent($ticketMessage->ticket()->first(), $ticketMessage));
    }

    public function deleting(TicketMessage $ticketMessage)
    {
        $ticketMessage->uploads()->get()->each(fn(TicketUpload $ticketUpload) => $ticketUpload->delete());
        Storage::disk(config('laravel-tickets.file.driver'))
            ->deleteDirectory(config('laravel-tickets.file.path') . $ticketMessage->id);
    }

}
