<div class="card card-body border-0 shadow">
    @if(session('status'))
        <div class="mb-4">
            <div class="py-2 fs-5 text-success">Данные загружены</div>
            <div class="upload-reestr__upload-info">
                @if(session('upload_rows_count'))<span>Загружено: {{ session('upload_rows_count') }},</span>@endif
                @if(session('invalid_rows_count'))
                    <a class="text-danger ms-2 upload-reestr__error-count" href="#error_reestr_upload">Ошибки: {{ session('invalid_rows_count') }}</a>
                @else
                    <a class="ms-2">Ошибки: 0</a>
                @endif
            </div>
        </div>
    @endif
    <div>
        <form action="{{ route('admin.reestr_upload') }}" enctype="multipart/form-data" method="post">
            @csrf
            <div class="mb-5 col-xl-4">
                <label for="file">{{ __('Загрузить файл (.xlsx)') }}</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <div class="mb-5">
                <input type="checkbox" name="check" id="check" class="form-check-input me-2" checked>
                <label class="form-check-label" for="check">{{ __('Очистить таблицу реестра') }}</label>
                <div class="text-danger ms-4 ps-1" style="font-size:14px;">{{ __('Внимание: если данный параметр включен, вся таблица реестра будет очищена и загрузятся новые данные из выбранного файла.') }}
                <br>{{ __('Если параметр отключен, то к существующим записям реестра добавятся данные из выбранного файла.') }}</div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-success text-white">{{ __('Загрузить') }}</button>
                <a href="{{ route('admin.reestr_load') }}" class="btn btn-gray-500 text-white ms-2">Отмена</a>
            </div>
        </form>
    </div>
</div>

@if(session('invalid_rows'))
    <div class="card card-body border-0 shadow mt-3 mb-5">
    <div class="error-reestr-upload" id="error_reestr_upload">
        <div class="text-danger mb-3 error-reestr-upload__title">Ошибки при загрузке:</div>
        <div class="error-reestr-upload__content">
            @foreach(session('invalid_rows') as $item)
                <div class="error-reestr-upload__item">
                    @foreach($item['errors'] as $error)
                        <div class="error-reestr-upload__item-error">{{ $error }}</div>
                    @endforeach
                    <div class="error-reestr-upload__item-info">Строка {{ $item['row'] }}: <span class="error-reestr-upload__item-email text-info">{{ $item['email'] }}</span></div>
                </div>
                <hr>
            @endforeach
        </div>
    </div>
    </div>
@endif
