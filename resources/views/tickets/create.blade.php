@extends(config('laravel-tickets.layouts'))

@section('content')
    <div class="card">
        <div class="card-header">
            @lang('Open ticket')
        </div>
        <div class="card-body">
            @includeWhen(session()->has('message'), 'laravel-tickets::alert', ['type' => 'info', 'message' => session()->get('message')])
            <form method="post" action="{{ route('laravel-tickets.tickets.store') }}" @if (config('laravel-tickets.files'))
                enctype="multipart/form-data"
            @endif>
                @csrf
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>@lang('Priority')</label>
                            <select class="form-control @error('priority') is-invalid @enderror" name="priority">
                                @foreach (config('laravel-tickets.priorities') as $priority)

                                    <option value="{{ $priority }}" @if (old('priority') === $priority)
                                    selected
                                        @endif>@lang(ucfirst(strtolower($priority)))</option>
                                @endforeach
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
                                   placeholder="@lang('Subject')" value="{{ old('subject') }}">
                            @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>@lang('Message')</label>
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      placeholder="@lang('Message')" name="message">{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if (config('laravel-tickets.files'))
                        <div class="col-12 mb-2">
                            <div class="custom-file">
                                <input type="file" name="files[]" multiple
                                       class="custom-file-input @error('files') is-invalid @enderror" id="files">
                                <label class="custom-file-label" for="files">@lang('Choose files')</label>
                                @error('files')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <button class="btn btn-primary">@lang('Create')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
