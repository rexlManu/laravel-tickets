<?php


namespace RexlManu\LaravelTickets\Observers;


use RexlManu\LaravelTickets\Models\TicketMessage;
use RexlManu\LaravelTickets\Models\TicketUpload;

class TicketMessageObserver
{

    public function deleting(TicketMessage $ticketMessage)
    {
        $ticketMessage->uploads()->get()->each(fn(TicketUpload $ticketUpload) => $ticketUpload->delete());
        \Storage::disk(config('laravel-tickets.file.driver'))
            ->deleteDirectory(config('laravel-tickets.file.path') . $ticketMessage->id);
    }

}
