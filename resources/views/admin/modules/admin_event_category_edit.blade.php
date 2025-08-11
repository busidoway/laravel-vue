<div class="row">
    <div class="col-md-12 col-sm-12 mb-4">
        <div class="card card-module border-0 shadow components-section">
            <div class="card-body">
                @if(session('status') === true)
                <div class="py-2 mb-4 fs-5 text-success">Успешно сохранено!</div>
                @elseif(session('status') === false)
                <div class="py-2 mb-4 fs-5 text-warning">{{ $errors }}</div>
                @endif
                <form action="@if(isset($event_category)){{ route('event_category.update', $event_category->id) }}@else{{ route('event_category.store') }}@endif" method="post">
                    @csrf
                    @isset($event_category) @method('PUT') @endisset
                    <div class="row mb-4 mx-0">
                        <label for="title">Заголовок *</label>
                        <div class="input-group">
                            <textarea name="title" id="title" class="form-control" rows="1" placeholder="Заголовок" required>@isset($event_category){{ $event_category->title }}@endisset</textarea>
                        </div>
                    </div>
                    <div class="row mb-4 mx-0">
                        <label for="title">Код</label>
                        <div class="input-group">
                            <textarea name="code" id="code" class="form-control" rows="1" placeholder="Код">@isset($event_category){{ $event_category->code }}@endisset</textarea>
                        </div>
                    </div>
                    <div class="row mb-4 mx-0 ps-3" style="font-size:14px;">
                        * Обязательно для заполнения
                    </div>
                    <div class="mx-0 mb-3 row">
                        <div class="button-group">
                            <button type="submit" class="btn btn-success text-white">Сохранить</button>
                            <a href="{{ route('admin.event_category') }}" class="btn btn-gray-500 text-white ms-2">Назад</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>