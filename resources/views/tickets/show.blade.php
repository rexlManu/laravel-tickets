<div class="row">
    <div class="col-8">
        @includeWhen(session()->has('message'), 'laravel-tickets::alert', ['type' => 'info', 'message' => session()->get('message')])

        @if (config('laravel-tickets.open-ticket-with-answer') || $ticket->state !== 'CLOSED')
            <div class="card mb-3">
                <div class="card-header">
                    @lang('Ticket answer')
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('laravel-tickets.tickets.message', compact('ticket')) }}">
                        @csrf
                        <textarea class="form-control @error('message') is-invalid @enderror"
                                  placeholder="@lang('Message')" name="message"></textarea>
                        @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <button class="btn btn-primary float-right mt-2">@lang('Send')</button>
                    </form>
                </div>
            </div>
        @endif

        @foreach ($messages as $message)
            <div class="card @if (! $loop->first)
                mt-2
@endif">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            {{ $message->user()->exists() ? $message->user->email : trans('Deleted user') }}
                        </div>
                        <div class="col-auto">
                            {{ $message->created_at->format(config('laravel-tickets.datetime-format')) }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! nl2br(e($message->message)) !!}
                </div>
            </div>
        @endforeach

        <div class="mt-2 d-flex justify-content-center">
            {!! $messages->links('pagination::bootstrap-4') !!}
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                @lang('Ticket overview')
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>@lang('Subject'):</label>
                    <input class="form-control" type="text" value="{{ $ticket->subject }}" disabled>
                </div>
                <div class="form-group">
                    <label>@lang('Priority'):</label>
                    <input class="form-control" type="text" value="@lang(ucfirst(strtolower($ticket->priority)))"
                           disabled>
                </div>
                <div class="form-group">
                    <label>@lang('State'):</label>
                    <input class="form-control" type="text" value="@lang(ucfirst(strtolower($ticket->state)))"
                           disabled>
                </div>
                @if ($ticket->state !== 'CLOSED')
                    <form method="post" action="{{ route('laravel-tickets.tickets.close', compact('ticket')) }}">
                        @csrf
                        <button class="btn btn-block btn-danger">@lang('Close ticket')</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
