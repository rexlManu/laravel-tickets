<?php


namespace RexlManu\LaravelTickets\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RexlManu\LaravelTickets\Models\Ticket;
use RexlManu\LaravelTickets\Models\TicketMessage;

class TicketMessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, TicketMessage $ticketMessage)
    {
        $this->ticket = $ticket;
        $this->message = $ticketMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('ticket-message');
    }
}
