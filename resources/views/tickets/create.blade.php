<div class="card">
    <div class="card-header">
        @lang('Open ticket')
    </div>
    <div class="card-body">
        @includeWhen(session()->has('message'), 'laravel-tickets::alert', ['type' => 'info', 'message' => session()->get('message')])
        <form method="post" action="{{ route('laravel-tickets.tickets.store') }}">
            @csrf
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label>@lang('Priority')</label>
                        <select class="form-control @error('priority') is-invalid @enderror" name="priority">
                            <option value="LOW">@lang('Low')</option>
                            <option value="MID">@lang('Mid')</option>
                            <option value="HIGH">@lang('High')</option>
                        </select>
                        @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label>@lang('Subject')</label>
                        <input class="form-control @error('subject') is-invalid @enderror" name="subject"
                               placeholder="@lang('Subject')">
                        @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>@lang('Message')</label>
                        <textarea class="form-control @error('message') is-invalid @enderror"
                                  placeholder="@lang('Message')" name="message"></textarea>
                        @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">@lang('Create')</button>
                </div>
            </div>
        </form>
    </div>
</div>
