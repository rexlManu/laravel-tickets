<div style="height: 28.15vh; overflow-y: scroll">
    @foreach ($ticket->messages()->with('uploads')->get() as $message)
        @foreach($message->uploads()->get() as $upload)
            <div class="card mt-2">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('laravel-tickets.tickets.download', ['ticket' => $ticket, 'ticketUpload' => $upload]) }}">{{ basename($upload->path) }}</a>
                                </div>
                                <div class="col-12 ">
                                                <span class="text-muted">
                                                    {{ $message->user->name }} {{ $upload->created_at->format(config('laravel-tickets.datetime-format')) }}
                                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>
