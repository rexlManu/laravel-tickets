<?php


namespace RexlManu\LaravelTickets\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RexlManu\LaravelTickets\Events\TicketCloseEvent;
use RexlManu\LaravelTickets\Events\TicketMessageEvent;
use RexlManu\LaravelTickets\Events\TicketOpenEvent;
use RexlManu\LaravelTickets\Models\Ticket;
use RexlManu\LaravelTickets\Models\TicketMessage;

/**
 * Class TicketController
 *
 * The main logic of the ticket system. All actions are performed here.
 *
 * If the accept header is json, the response will be a json response
 *
 * @package RexlManu\LaravelTickets\Controllers
 */
class TicketController extends Controller
{

    /**
     * TicketController constructor
     */
    public function __construct()
    {
        if (! config('laravel-tickets::permission')) {
            return;
        }

        $this->middleware(config('laravel-tickets::permissions.list-ticket'))->only('index');
        $this->middleware(config('laravel-tickets::permissions.create-ticket'))->only('store');
        $this->middleware(config('laravel-tickets::permissions.close-ticket'))->only('close');
        $this->middleware(config('laravel-tickets::permissions.show-ticket'))->only('show');
        $this->middleware(config('laravel-tickets::permissions.message-ticket'))->only('message');
    }

    /**
     * Show every ticket that the user has created
     *
     * If the accept header is json, the response will be a json response
     *
     * @return View|JsonResponse
     */
    public function index()
    {
        $tickets = request()->user()->tickets()->get();

        return request()->wantsJson() ?
            response()->json(compact('tickets')) :
            view('laravel-tickets::tickets.index',
                compact('tickets')
            );
    }

    /**
     * Creates a ticket
     *
     * @param Request $request the request
     *
     * @return View|JsonResponse|RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => [ 'required', 'string', 'max:191' ],
            'priority' => [ 'required', Rule::in([ 'LOW', 'MID', 'HIGH' ]) ],
            'message' => [ 'required', 'string' ]
        ]);
        if ($request->user()->tickets()->where('state', '!=', 'CLOSED')->count() >= config('laravel-tickets::maximal-open-tickets')) {
            $message = trans('You have reached the limit of open tickets');
            return \request()->wantsJson() ?
                response()->json(compact('message')) :
                back()->with(
                    'message',
                    $message
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

        $message = trans('The ticket was successfully created');
        return $request->wantsJson() ?
            response()->json(compact('message', 'ticket', 'ticketMessage')) :
            redirect(route(
                'laravel-tickets::tickets.show',
                compact('ticket')
            ))->with(
                'message',
                $message
            );
    }

    /**
     * Show detailed informations about the ticket and the informations
     *
     * @param Ticket $ticket
     *
     * @return View|JsonResponse|RedirectResponse|void
     */
    public function show(Ticket $ticket)
    {
        if ($ticket->user()->get()->contains(\request()->user())) {
            return abort(403);
        }

        $messages = $ticket->messages()->get();

        return \request()->wantsJson() ?
            response()->json(compact(
                'ticket',
                'messages'
            )) :
            view('laravel-tickets::tickets.show',
                compact(
                    'ticket',
                    'messages'
                )
            );
    }

    /**
     * Send a message to the ticket
     *
     * @param Request $request
     * @param Ticket $ticket
     *
     * @return JsonResponse|RedirectResponse|void
     */
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

        $ticket->update([ 'state' => 'OPEN' ]);

        event(new TicketMessageEvent($ticket, $ticketMessage));

        $message = trans('Your answer was sent successfully');
        return $request->wantsJson() ?
            response()->json(compact('message')) :
            back()->with(
                'message',
                $message
            );
    }

    /**
     * Declare the ticket as closed.
     *
     * @param Ticket $ticket
     *
     * @return JsonResponse|RedirectResponse|void
     */
    public function close(Ticket $ticket)
    {
        if ($ticket->user()->get()->contains(\request()->user())) {
            return abort(403);
        }
        if ($ticket->state === 'CLOSED') {
            $message = trans('The ticket is already closed');
            return \request()->wantsJson() ?
                response()->json(compact('message')) :
                back()->with(
                    'message',
                    $message
                );
        }
        $ticket->update([ 'state' => 'CLOSED' ]);
        event(new TicketCloseEvent($ticket));

        $message = trans('The ticket was successfully closed');
        return \request()->wantsJson() ?
            response()->json(compact('message')) :
            back()->with(
                'message',
                $message
            );
    }

}
