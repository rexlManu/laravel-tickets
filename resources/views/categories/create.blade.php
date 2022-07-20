@extends(config('laravel-tickets.layouts'))

@section('content')
<div class="card">
    <div class="card-header">
        @lang('Add new category')
    </div>
    <div class="card-body">
        @includeWhen(session()->has('message'), 'laravel-tickets::alert', ['type' => 'info', 'message' =>
        session()->get('message')])
        <form method="post" action="{{ route('laravel-tickets.categories.store') }}"
            @if(config('laravel-tickets.files')) enctype="multipart/form-data" @endif>
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>@lang('Translation')</label>
                        <textarea class="form-control @error('translation') is-invalid @enderror"
                            placeholder="@lang('translation')" name="translation">{{ old('translation') }}</textarea>
                        @error('translation')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <button class="btn btn-primary">@lang('Create')</button>
                    <a href="{{ route('laravel-tickets.categories.index') }}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection