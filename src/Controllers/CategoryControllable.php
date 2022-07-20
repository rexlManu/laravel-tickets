<?php


namespace RexlManu\LaravelTickets\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RexlManu\LaravelTickets\Models\Ticket;
use RexlManu\LaravelTickets\Models\TicketCategory;
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
trait CategoryControllable
{

    /**
     * @link CategoryControllable constructor
     */
    public function __construct()
    {
        if (!config('laravel-tickets.permission')) {
            return;
        }

        $this->middleware(config('laravel-tickets.permissions.list-category'))->only('index');
        $this->middleware(config('laravel-tickets.permissions.create-category'))->only('store', 'create');
        $this->middleware(config('laravel-tickets.permissions.show-category'))->only('show');
        $this->middleware(config('laravel-tickets.permissions.edit-category'))->only('edit');
    }

    /**
     * Show every @return View|JsonResponse
     *
     * @link TicketCategory that the user has created
     *
     * If the accept header is json, the response will be a json response
     *
     */
    public function index()
    {
        $categories = TicketCategory::orderBy('id', 'desc')->paginate(10);

        return request()->wantsJson() ?
            response()->json(compact('categories')) :
            view(
                'laravel-tickets::categories.index',
                compact('categories')
            );
    }

    /**
     * Show the create form
     *
     * @return View
     */
    public function create()
    {
        return view('laravel-tickets::categories.create');
    }

    /**
     * Creates a @param Request $request the request
     *
     * @return View|JsonResponse|RedirectResponse
     * @link TicketCategory
     *
     */
    public function store(Request $request)
    {
        $rules = [
            'translation' => ['required', 'string', 'max:191'],
        ];
        $data = $request->validate($rules);

        if ($request->has('action') && $request->action == 'edit') {
            $category = TicketCategory::where('id', $request->category_id)->update(
                $request->only('translation')
            );
            $message = trans('The category was successfully updated');
        } else {
            $category = TicketCategory::create(
                $request->only('translation')
            );
            $message = trans('The category was successfully created');
        }

        return $request->wantsJson() ?
            response()->json(compact('category')) :
            redirect(route(
                'laravel-tickets.categories.index'
            ))->with([
                'message' => $message,
                'type' => 'success'
            ]);
    }

    /**
     * Show detailed informations about the @param TicketCategory $category
     *
     * @return View|JsonResponse|RedirectResponse|void
     * @link TicketCategory and the informations
     *
     */
    public function show(TicketCategory $category)
    {
        if (
            !request()->user()->can(config('laravel-tickets.permissions.all-ticket'))
        ) {
            return abort(403);
        }
        return \request()->wantsJson() ?
            response()->json(compact(
                'category',
            )) :
            view(
                'laravel-tickets::categories.show',
                compact(
                    'category',
                )
            )->with(['action' => 'show']);
    }
    /**
     * edit detailed informations about the @param TicketCategory $category
     *
     * @return View|JsonResponse|RedirectResponse|void
     * @link TicketCategory and the informations
     *
     */
    public function edit(TicketCategory $category)
    {
        if (
            !request()->user()->can(config('laravel-tickets.permissions.all-ticket'))
        ) {
            return abort(403);
        }
        return \request()->wantsJson() ?
            response()->json(compact(
                'category',
            )) :
            view(
                'laravel-tickets::categories.show',
                compact(
                    'category',
                )
            )->with(['action' => 'edit']);
    }


    public function destroy(TicketCategory $category)
    {
        if (
            !request()->user()->can(config('laravel-tickets.permissions.all-ticket'))
        ) {
            return abort(403);
        }

        $category->delete();

        $message = trans('The category was successfully deleted');

        return \request()->wantsJson() ?
            response()->json(compact(
                'message',
            )) :
            redirect()->route('laravel-tickets.categories.index')
            ->with([
                'message' => $message,
                'type' => 'success'
            ]);
    }
}
