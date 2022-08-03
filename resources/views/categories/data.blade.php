@extends(config('laravel-tickets.layouts'))

@section('content')
<div class="card">
    <div class="card-header">
        @lang(Str::ucfirst($action.' category'))
    </div>
    <div class="card-body">
        <livewire:laravel-tickets::category-form :action="$action" :category="$category" />
    </div>
</div>
@endsection