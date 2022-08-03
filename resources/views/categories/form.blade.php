<div>
    <form wire:submit.prevent="store">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label>@lang('Translation')</label>
                    <textarea class="form-control @error('translation') is-invalid @enderror"
                        placeholder="@lang('translation')" wire:model.defer="translation" name="translation"
                        @if($action=='show' ) disabled @endif>{{ old('translation') }}</textarea>
                    @error('translation')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12 mt-2">
                @if ($action!='show')
                <button class="btn btn-success m-1">
                    @if ($action=='add')
                    @lang('Create')
                    @elseif ($action=='edit')
                    @lang('Save')
                    @endif
                </button>
                <a href="{{ route('laravel-tickets.categories.index') }}" class="btn btn-danger">@lang('Cancel')</a>
                @else
                <a href="{{ route('laravel-tickets.categories.index') }}" class="btn btn-primary">@lang('Back')</a>
                @endif
            </div>
        </div>
    </form>
</div>