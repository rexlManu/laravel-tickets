<?php


namespace RexlManu\LaravelTickets\Observers;


use RexlManu\LaravelTickets\Models\TicketUpload;

class TicketUploadObserver
{

    public function deleting(TicketUpload $ticketUpload)
    {
        \Storage::disk(config('laravel-tickets.file.driver'))->delete($ticketUpload->path);
    }

}
