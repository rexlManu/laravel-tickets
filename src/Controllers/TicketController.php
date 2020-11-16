<?php


namespace RexlManu\LaravelTickets\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use RexlManu\LaravelTickets\Events\TicketCloseEvent;
use RexlManu\LaravelTickets\Events\TicketMessageEvent;
use RexlManu\LaravelTickets\Events\TicketOpenEvent;
use RexlManu\LaravelTickets\Models\Ticket;
use RexlManu\LaravelTickets\Models\TicketMessage;

class TicketController extends Controller
{

    public function index()
    {
        $tickets = request()->user()->tickets()->get();
        return view(
            'laravel-tickets::tickets.index',
            compact('tickets')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => [ 'required', 'string', 'max:191' ],
            'priority' => [ 'required', Rule::in([ 'LOW', 'MID', 'HIGH' ]) ],
            'state' => [ 'required', Rule::in([ 'OPEN', 'ANSWERED', 'CLOSED' ]) ],
            'message' => [ 'required', 'string' ]
        ]);
        if ($request->user()->tickets()->where('state', '!=', 'CLOSED')->count() >= config('laravel-tickets::maximal-open-tickets')) {
            return back()->with(
                'message',
                trans('You have reached the limit of open tickets')
            );
        }
        $ticket = $request->user()->tickets()->create(
            $data
        );
        $ticketMessage = new TicketMessage($data);
        $ticketMessage->user()->associate($request->user());
        $ticketMessage->ticket()->associate($ticket);
        $ticketMessage->save();

        event(new TicketOpenEvent($ticket));

        return redirect(route(
            'laravel-tickets::tickets.show',
            compact('ticket')
        ))->with(
            'message',
            trans('The ticket was successfully created')
        );
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user()->get()->contains(\request()->user())) {
            return abort(403);
        }

        $messages = $ticket->messages()->get();
        return view(
            'laravel-tickets::tickets.show',
            compact(
                'ticket',
                'messages'
            )
        );
    }

    public function message(Request $request, Ticket $ticket)
    {
        if ($ticket->user()->get()->contains(\request()->user())) {
            return abort(403);
        }

        $data = $request->validate([
            'message' => [ 'required', 'string' ]
        ]);

        $ticketMessage = new TicketMessage($data);
        $ticketMessage->user()->associate($request->user());
        $ticketMessage->ticket()->associate($ticket);
        $ticketMessage->save();

        $ticket->update([ 'state' => 'ANSWERED' ]);

        event(new TicketMessageEvent($ticket, $ticketMessage));

        return back()->with(
            'message',
            trans('Your answer was sent successfully')
        );
    }

    public function close(Ticket $ticket)
    {
        if ($ticket->user()->get()->contains(\request()->user())) {
            return abort(403);
        }
        if ($ticket->state === 'CLOSED') {
            return back()->with(
                'message',
                trans('The ticket is already closed')
            );
        }
        $ticket->update([ 'state' => 'CLOSED' ]);
        event(new TicketCloseEvent($ticket));
        return back()->with(
            'message',
            'The ticket was successfully closed'
        );
    }

}
