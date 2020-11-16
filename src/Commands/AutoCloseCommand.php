<?php


namespace RexlManu\LaravelTickets\Commands;


use Carbon\Carbon;
use Illuminate\Console\Command;
use RexlManu\LaravelTickets\Events\TicketCloseEvent;
use RexlManu\LaravelTickets\Events\TicketUpdateEvent;
use RexlManu\LaravelTickets\Models\Ticket;

class AutoCloseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:autoclose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close any ticket that has become inactive.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tickets = Ticket::query()->where('updated_at', '<', Carbon::now()->subDays(7));
        $tickets->update(['state' => 'CLOSED']);
        $tickets->get()->each(function ($ticket) {
            event(new TicketCloseEvent($ticket));
        });
    }
}
