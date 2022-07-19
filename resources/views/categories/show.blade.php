@extends(config('laravel-tickets.layouts'))

@section('content')
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-8">
        @includeWhen(session()->has('message'), 'laravel-tickets::alert', ['type' => 'info', 'message' =>
        session()->get('message')])
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                @lang('Category overview')
            </div>
            <div class="card-body">
                <form @if ($action=='edit' ) method="post" action="{{ route('laravel-tickets.categories.store') }}"
                    @endif>
                    <input type="hidden" name="action" value="{{$action}}">
                    @if ($action=='edit')
                    <input type="hidden" name="category_id" value="{{$category->id}}">
                    @csrf
                    @endif
                    <div class="form-group">
                        <label>@lang('Translation'):</label>
                        <input class="form-control" name="translation" type="text" value="{{ $category->translation }}"
                            @if($action!='edit' ) disabled @endif>
                    </div>
                    <div class="form-group mt-2 d-flex">
                        @if ($action=='edit')
                        <button class="btn btn-success m-1">{{__('Save')}}</button>
                        @endif
                        <a href="{{ route('laravel-tickets.categories.index') }}"
                            class="btn btn-primary m-1">@lang('Back')</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection