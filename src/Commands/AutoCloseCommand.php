<?php


namespace RexlManu\LaravelTickets\Commands;

use Illuminate\Console\Command;
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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Ticket::query()->state([ 'OPEN', 'ANSWERED' ])->where(
            'updated_at',
            '<',
            now()->subDays(config('laravel-tickets.autoclose-days'))
        )->each(function (Ticket $ticket) {
            $ticket->update([ 'state' => 'CLOSED' ]);
        });
    }
}
