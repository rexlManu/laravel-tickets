@extends(config('laravel-tickets.layouts'))

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8">
            @includeWhen(session()->has('message'), 'laravel-tickets::alert', ['type' => 'info', 'message' => session()->get('message')])

            @if (config('laravel-tickets.open-ticket-with-answer') || $ticket->state !== 'CLOSED')
                <div class="card mb-3">
                    <div class="card-header">
                        @lang('Ticket answer')
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('laravel-tickets.tickets.message', compact('ticket')) }}"
                              @if (config('laravel-tickets.files')) enctype="multipart/form-data" @endif>
                            @csrf
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      placeholder="@lang('Message')" name="message">{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if (config('laravel-tickets.files'))
                                <div class="custom-file mt-2">
                                    <input type="file" name="files[]" multiple
                                           class="custom-file-input @error('files') is-invalid @enderror {{ empty($errors->get('files.*'))?'':'is-invalid' }}"
                                           id="files">
                                    <label class="custom-file-label" for="files">@lang('Choose files')</label>
                                    @foreach($errors->get('files.*') as $value)
                                        <div class="invalid-feedback">{{ $value[0] }}</div>
                                    @endforeach

                                    @error('files')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <button class="btn btn-primary float-right mt-2">@lang('Send')</button>
                        </form>
                    </div>
                </div>
            @endif
            @php($messagesPagination = $messages->paginate(4))
            @foreach ($messagesPagination as $message)
                <div class="card @if (! $loop->first) mt-2 @endif">
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
                        <div>
                            {!! nl2br(e($message->message)) !!}
                        </div>
                    </div>
                    @if ($message->uploads()->count() > 0)
                        <div class="card-body border-top p-1">
                            <div class="row mt-1 mb-2 pr-2 pl-2">
                                @foreach ($message->uploads()->get() as $ticketUpload)
                                    <div class="col">
                                        <a
                                            href="{{ route('laravel-tickets.tickets.download', compact('ticket', 'ticketUpload')) }}"
                                        >{{ basename($ticketUpload->path) }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach

            <div class="mt-2 d-flex justify-content-center">
                {!! $messagesPagination->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    @lang('Ticket overview')
                </div>
                <div class="card-body">
                    @if (config('laravel-tickets.category') && $ticket->category()->exists())
                        <div class="form-group">
                            <label>@lang('Category'):</label>
                            <input class="form-control" type="text"
                                   value="{{ $ticket->category()->first()->translation }}" disabled>
                        </div>
                    @endif
                    @if (config('laravel-tickets.references') && $ticket->reference()->exists())
                        <div class="form-group">
                            <label>@lang('Reference'):</label>
                            @php($referenceable = $ticket->reference->referenceable)
                            <input class="form-control" type="text"
                                   value="{{ $referenceable->toReference() }}" disabled>
                        </div>
                    @endif
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

            <ul class="nav nav-pills mb mt-2" id="pills-tab">
                @if (config('laravel-tickets.list.users'))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-users-tab" data-toggle="pill"
                           href="#pills-users">@lang('Users')</a>
                    </li>
                @endif
                @if (config('laravel-tickets.list.files'))
                    <li class="nav-item">
                        <a class="nav-link" id="pills-files-tab" data-toggle="pill"
                           href="#pills-files">@lang('Files')</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="pills-users">
                    @include('laravel-tickets::tickets.partials.users', compact('ticket', 'messages'))
                </div>
                <div class="tab-pane fade" id="pills-files">
                    @include('laravel-tickets::tickets.partials.files', compact('ticket', 'messages'))
                </div>
            </div>

        </div>
    </div>
@endsection
