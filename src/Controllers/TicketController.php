<?php


namespace RexlManu\LaravelTickets\Controllers;


use Illuminate\Database\Eloquent\Relations\Relation;
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
use RexlManu\LaravelTickets\Models\TicketReference;
use RexlManu\LaravelTickets\Models\TicketUpload;
use RexlManu\LaravelTickets\Rule\TicketReferenceRule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     * @link TicketController constructor
     */
    public function __construct()
    {
        if (! config('laravel-tickets.permission')) {
            return;
        }

        $this->middleware(config('laravel-tickets.permissions.list-ticket'))->only('index');
        $this->middleware(config('laravel-tickets.permissions.create-ticket'))->only('store', 'create');
        $this->middleware(config('laravel-tickets.permissions.close-ticket'))->only('close');
        $this->middleware(config('laravel-tickets.permissions.show-ticket'))->only('show');
        $this->middleware(config('laravel-tickets.permissions.message-ticket'))->only('message');
        $this->middleware(config('laravel-tickets.permissions.download-ticket'))->only('download');
    }

    /**
     * Show every @return View|JsonResponse
     *
     * @link Ticket that the user has created
     *
     * If the accept header is json, the response will be a json response
     *
     */
    public function index()
    {
        $tickets = request()->user()->tickets()->orderBy('id', 'desc')->paginate(10);

        return request()->wantsJson() ?
            response()->json(compact('tickets')) :
            view('laravel-tickets::tickets.index',
                compact('tickets')
            );
    }

    /**
     * Show the create form
     *
     * @return View
     */
    public function create()
    {
        return view('laravel-tickets::tickets.create');
    }

    /**
     * Creates a @param Request $request the request
     *
     * @return View|JsonResponse|RedirectResponse
     * @link Ticket
     *
     */
    public function store(Request $request)
    {
        $rules = [
            'subject' => [ 'required', 'string', 'max:191' ],
            'priority' => [ 'required', Rule::in(config('laravel-tickets.priorities')) ],
            'message' => [ 'required', 'string' ],
            'files' => [ 'max:' . config('laravel-tickets.file.max-files') ],
            'files.*' => [
                'sometimes',
                'file',
                'max:' . config('laravel-tickets.file.size-limit'),
                'mimes:' . config('laravel-tickets.file.memes'),
            ],
        ];
        if (config('laravel-tickets.category')) {
            $rules[ 'category_id' ] = [
                'required',
                Rule::exists(config('laravel-tickets.database.ticket-categories-table'), 'id'),
            ];
        }
        if (config('laravel-tickets.references')) {
            $rules[ 'reference' ] = [
                config('laravel-tickets.references-nullable') ? 'nullable' : 'required',
                new TicketReferenceRule(),
            ];
        }
        $data = $request->validate($rules);
        if ($request->user()->tickets()->where('state', '!=', 'CLOSED')->count() >= config('laravel-tickets.maximal-open-tickets')) {
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

        if (array_key_exists('reference', $data)) {
            $reference = explode(',', $data[ 'reference' ]);
            $ticketReference = new TicketReference();
            $ticketReference->ticket()->associate($ticket);
            $ticketReference->referenceable()->associate(
                resolve($reference[ 0 ])->find($reference[ 1 ])
            );
            $ticketReference->save();
        }

        $ticketMessage = new TicketMessage($data);
        $ticketMessage->user()->associate($request->user());
        $ticketMessage->ticket()->associate($ticket);
        $ticketMessage->save();

        $this->handleFiles($data[ 'files' ] ?? [], $ticketMessage);

        event(new TicketOpenEvent($ticket));

        $message = trans('The ticket was successfully created');
        return $request->wantsJson() ?
            response()->json(compact('message', 'ticket', 'ticketMessage')) :
            redirect(route(
                'laravel-tickets.tickets.show',
                compact('ticket')
            ))->with(
                'message',
                $message
            );
    }

    /**
     * Show detailed informations about the @param Ticket $ticket
     *
     * @return View|JsonResponse|RedirectResponse|void
     * @link Ticket and the informations
     *
     */
    public function show(Ticket $ticket)
    {
        if (! $ticket->user()->get()->contains(\request()->user())) {
            return abort(403);
        }

        $messages = $ticket->messages()->with('uploads')->orderBy('created_at', 'desc')->paginate(4);

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
     * Send a message to the @param Request $request
     *
     * @param Ticket $ticket
     *
     * @return JsonResponse|RedirectResponse|void
     * @link Ticket
     *
     */
    public function message(Request $request, Ticket $ticket)
    {
        if (! $ticket->user()->get()->contains(\request()->user())) {
            return abort(403);
        }

        if (! config('laravel-tickets.open-ticket-with-answer') && $ticket->state === 'CLOSED') {
            $message = trans('You cannot reply to a closed ticket');
            return \request()->wantsJson() ?
                response()->json(compact('message')) :
                back()->with(
                    'message',
                    $message
                );
        }

        $data = $request->validate([
            'message' => [ 'required', 'string' ],
            'files' => [ 'max:' . config('laravel-tickets.file.max-files') ],
            'files.*' => [
                'sometimes',
                'file',
                'max:' . config('laravel-tickets.file.size-limit'),
                'mimes:' . config('laravel-tickets.file.memes'),
            ]
        ]);

        $ticketMessage = new TicketMessage($data);
        $ticketMessage->user()->associate($request->user());
        $ticketMessage->ticket()->associate($ticket);
        $ticketMessage->save();

        $this->handleFiles($data[ 'files' ] ?? [], $ticketMessage);

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
     * Declare the @param Ticket $ticket
     *
     * @return JsonResponse|RedirectResponse|void
     * @link Ticket as closed.
     *
     */
    public function close(Ticket $ticket)
    {
        if (! $ticket->user()->get()->contains(\request()->user())) {
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

    /**
     * Downloads the file from @param Ticket $ticket
     *
     * @param TicketUpload $ticketUpload
     *
     * @return BinaryFileResponse
     * @link TicketUpload
     *
     */
    public function download(Ticket $ticket, TicketUpload $ticketUpload)
    {
        if (! $ticket->user()->get()->contains(\request()->user()) ||
            ! $ticket->messages()->get()->contains($ticketUpload->message()->first())) {
            return abort(403);
        }

        $storagePath = storage_path('app/' . $ticketUpload->path);
        if (config('laravel-tickets.pdf-force-preview') && pathinfo($ticketUpload->path, PATHINFO_EXTENSION) === 'pdf') {
            return response()->file($storagePath);
        }

        return response()->download($storagePath);
    }

    /**
     * Handles the uploaded files for the @param $files array uploaded files
     *
     * @param TicketMessage $ticketMessage
     *
     * @link TicketMessage
     *
     */
    private function handleFiles($files, TicketMessage $ticketMessage)
    {
        if (! config('laravel-tickets.files') || $files === null) {
            return;
        }
        foreach ($files as $file) {
            $ticketMessage->uploads()->create([
                'path' => $file->storeAs(
                    config('laravel-tickets.file.path') . $ticketMessage->id,
                    $file->getClientOriginalName(),
                    config('laravel-tickets.file.driver')
                )
            ]);
        }
    }

}
