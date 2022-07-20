@extends(config('laravel-tickets.layouts'))

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>
                {{__('Categories')}}
            </div>
            <div>
                <a href="{{ route('laravel-tickets.categories.create') }}" class="btn btn-primary">{{__('Add new')}}</a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @includeWhen(session()->has('message'), 'laravel-tickets::alert', ['message' => session()->get('message'),'type'
        => session()->get('type')])
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="th">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{__('Translation')}}</th>
                        <th scope="col">@lang('Last Update')</th>
                        <th scope="col">@lang('Created at')</th>
                        <th scope="col">{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <th scope="row">{{ $category->id }}</th>
                        <td>{{ $category->translation }}</td>
                        <td>{{ $category->updated_at ? $category->updated_at->format(config('laravel-tickets.datetime-format')) : trans('Not updated') }}
                        </td>
                        <td>{{ $category->created_at ? $category->created_at->format(config('laravel-tickets.datetime-format')) : trans('Not created') }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('laravel-tickets.categories.show', compact('category')) }}"
                                    class="btn btn-primary m-1">{{__('Show')}}</a>
                                <a href="{{ route('laravel-tickets.categories.edit', compact('category')) }}"
                                    class="btn btn-success m-1">{{__('Edit')}}</a>
                                <form method="post"
                                    action="{{ route('laravel-tickets.categories.destroy', compact('category')) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger m-1">{{__('Delete')}}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-2 d-flex justify-content-center">
                {!! $categories->links('pagination::bootstrap-4') !!}
            </div>
        </div>

    </div>
</div>
@endsection