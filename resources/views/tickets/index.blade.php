<div class="card">
    <div class="card-header">
        @lang('Tickets')
    </div>
    <div class="card-body">
        @includeWhen(session()->has('message'), 'laravel.tickets.alert', ['message' => session()->get('message')])

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="th">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('Subject')</th>
                    <th scope="col">@lang('Priority')</th>
                    <th scope="col">@lang('State')</th>
                    <th scope="col">@lang('Last Update')</th>
                    <th scope="col">@lang('Created at')</th>
                    <th scope="col">@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <th scope="row">{{ $ticket->id }}</th>
                        <td>{{ $ticket->subject }}</td>
                        <td>@lang(ucfirst(strtolower($ticket->priority)))</td>
                        <td>@lang(ucfirst(strtolower($ticket->state)))</td>
                        <td>{{ $ticket->updated_at ? $ticket->updated_at->format(config('laravel-tickets.datetime-format')) : trans('Not updated') }}</td>
                        <td>{{ $ticket->created_at ? $ticket->created_at->format(config('laravel-tickets.datetime-format')) : trans('Not created') }}</td>
                        <td>
                            <a href="{{ route('laravel-tickets.tickets.show', compact('ticket')) }}"
                               class="btn btn-primary">@lang('Show')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-2 d-flex justify-content-center">
                {!! $tickets->links('pagination::bootstrap-4') !!}
            </div>
        </div>

    </div>
</div>

