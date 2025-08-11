<div class="row">
    <div class="col-md-12 col-sm-12 mb-4">
        <div class="card card-module border-0 shadow components-section">
            <div class="card-body">
                @if(session('status') === true)
                <div class="py-2 mb-4 fs-5 text-success">Успешно сохранено!</div>
                @elseif(session('status') === false)
                <div class="py-2 mb-4 fs-5 text-warning">{{ $errors }}</div>
                @endif
                <form action="@if(isset($event_format)){{ route('event_format.update', $event_format->id) }}@else{{ route('event_format.store') }}@endif" method="post">
                    @csrf
                    @isset($event_format) @method('PUT') @endisset
                    <div class="row mb-4 mx-0">
                        <label for="title">Заголовок *</label>
                        <div class="input-group">
                            <textarea name="title" id="title" class="form-control" rows="1" required>@isset($event_format){{ $event_format->title }}@endisset</textarea>
                        </div>
                    </div>
                    <div class="row mb-4 mx-0">
                        <label for="title">Код</label>
                        <div class="input-group">
                            <textarea name="code" id="code" class="form-control" rows="1">@isset($event_format){{ $event_format->code }}@endisset</textarea>
                        </div>
                    </div>
                    <div class="row mb-4 mx-0">
                        <label for="title">Дополнительно</label>
                        <div class="input-group">
                            <textarea name="text" id="text" class="form-control" rows="3">@isset($event_format){{ $event_format->text }}@endisset</textarea>
                        </div>
                    </div>
                    <div class="row mb-4 mx-0 ps-3" style="font-size:14px;">
                        * Обязательно для заполнения
                    </div>
                    <div class="mx-0 mb-3 row">
                        <div class="button-group">
                            <button type="submit" class="btn btn-success text-white">Сохранить</button>
                            <a href="{{ route('admin.event_format') }}" class="btn btn-gray-500 text-white ms-2">Назад</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>