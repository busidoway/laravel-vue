<form action="@if(isset($events)){{ route('events.update', $events->id) }}@else{{ route('events.store') }}@endif" enctype="multipart/form-data" method="post">
@csrf
@isset($events) @method('PUT') @endisset
<div class="event-edit-container">
<div class="row mb-3 mx-0 event-edit-buttons">
    <div class="button-group">
        <button type="submit" class="btn btn-success text-white">Сохранить</button>
        <a href="{{ route('admin.events') }}" class="btn btn-gray-500 text-white ms-2">Назад</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 mb-4">
        <div class="card card-module border-0 shadow components-section">
            <div class="card-body">
                @if(session('status') === true)
                <div class="py-2 mb-4 fs-5 text-success">Успешно сохранено!</div>
                @elseif(session('status') === false)
                <div class="py-2 mb-4 fs-5 text-warning">{{ $errors }}</div>
                @endif

                    <ul class="nav nav-tabs" id="eventTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="main_tab" data-bs-toggle="tab" data-bs-target="#mainTab" type="button" role="tab" aria-controls="home" aria-selected="true">Основное</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="text_tab" data-bs-toggle="tab" data-bs-target="#textTab" type="button" role="tab" aria-controls="textTab" aria-selected="true">Текст</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="lector_tab" data-bs-toggle="tab" data-bs-target="#lectorTab" type="button" role="tab" aria-controls="lectorTab" aria-selected="true">Лекторы</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="slider_tab" data-bs-toggle="tab" data-bs-target="#sliderTab" type="button" role="tab" aria-controls="sliderTab" aria-selected="true">Слайдер</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="video_tab" data-bs-toggle="tab" data-bs-target="#videoTab" type="button" role="tab" aria-controls="videoTab" aria-selected="true">Видео</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="file_tab" data-bs-toggle="tab" data-bs-target="#fileTab" type="button" role="tab" aria-controls="fileTab" aria-selected="true">Файлы</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="period_tab" data-bs-toggle="tab" data-bs-target="#periodTab" type="button" role="tab" aria-controls="periodTab" aria-selected="true">Периоды доступа</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="tabContent">

                        <div class="tab-pane fade show active" id="mainTab" role="tabpanel" aria-labelledby="main_tab">

                            <div class="row mb-4 mx-0">
                                <label for="title">Заголовок</label>
                                <div class="input-group">
                                    <input type="text" name="title" id="title" class="form-control" value="@isset($events){{ $events->title }}@endisset" required>
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="subtitle">Подзаголовок</label>
                                <div class="textarea-group">
                                    <textarea name="subtitle" id="subtitle" class="form-control tinymce-editor-simple" rows="2" placeholder="Подзаголовок">@isset($events){{ $events->subtitle }}@endisset</textarea>
                                </div>
                            </div>
                            <div id="app_event_categories">
                                <event-categories
                                    @isset($event_category_join->event_category_id)
                                        v-bind:event-category-id="{{ $event_category_join->event_category_id }}"
                                    @endisset
                                    @isset($event_sub_category_join->event_sub_category_id)
                                        v-bind:event-sub-category-id="{{ $event_sub_category_join->event_sub_category_id }}"
                                    @endisset
                                ></event-categories>
                            </div>
{{--                            <div class="row mb-4 mx-0">--}}
{{--                                <label for="event_category">Категория</label>--}}
{{--                                <div class="col-xl-6 input-group">--}}
{{--                                    <select class="form-select" name="event_category" id="event_category" aria-label="select">--}}
{{--                                        <option value="">-- Выбрать категорию --</option>--}}
{{--                                        @foreach($event_category as $cat)--}}
{{--                                            <option value="{{ $cat->id }}" @if(isset($event_category_join) && $event_category_join->event_category_id == $cat->id){{ __('selected') }}@endif>{{ $cat->title }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div id="app_date_list">
                                <date-list
                                    @isset($events)
                                        v-bind:event-id="{{ $events->id }}"
                                    @endisset
                                >
                                </date-list>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="time">Время</label>
                                <div class="input-group">
                                    <input name="time" id="time" class="form-control" value="@isset($events){{ $events->time }}@endisset">
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="schedule">График мероприятий</label>
                                <div class="input-group">
                                    <input name="schedule" id="schedule" class="form-control"  value="@isset($events){{ $events->schedule }}@endisset">
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="place">Место проведения</label>
                                <div class="input-group">
                                    <input name="place" id="place" class="form-control"  value="@isset($events){{ $events->place }}@endisset">
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="time">Срок доступа</label>
                                <div class="input-group">
                                    <input name="period" id="period" class="form-control"  value="@isset($events){{ $events->period }}@endisset">
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="vol_program">Объем программы</label>
                                <div class="input-group">
                                    <input name="vol_program" id="vol_program" class="form-control"  value="@isset($events){{ $events->vol_program }}@endisset">
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="">Формат</label>
                                <div id="app_event_format">
                                    <event-format @if(isset($events)) v-bind:event-id='{{ $events->id }}' @endif ></event-format>
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="event_format">Свой формат</label>
                                <div class="input-group">
                                    <input name="event_format" id="event_format" class="form-control" value="@isset($events){{ $events->format }}@endisset">
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="price">Цена</label>
                                <div class="input-group">
                                    <input type="number" name="price" id="price" class="form-control" value="@isset($events){{ $events->price }}@endisset">
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="price_text">Цена (дополнительный текст)</label>
                                <div class="input-group">
                                    <input type="text" name="price_text" id="price_text" class="form-control" value="@isset($events){{ $events->price_text }}@endisset">
                                </div>
                            </div>
                            <div class="mb-2 mx-0 row">
                                <div class="d-flex">
                                    <label class="form-check-label" for="date_public">Цена для членов ФПНК</label>
                                </div>
                            </div>
                            <div class="mb-2 mx-0 row">
                                <div class="">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input form-check-green-input" type="checkbox" name="check_price_m" id="check_price_m" onclick="checkInp(this, 'price_m')" @isset($events)@if($events->price_m === 0) checked @endif @endisset>
                                        <label class="form-check-label" for="check_price_m">Бесплатно</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <div class="input-group">
                                    <input type="number" name="price_m" id="price_m" class="form-control" value="@isset($events){{ $events->price_m }}@endisset" @isset($events)@if($events->price_m === 0) disabled @endif @endisset>
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="url">URL (для перенаправления по другому адресу)</label>
                                <div class="input-group">
                                    <input name="url" id="url" class="form-control"  value="@isset($events){{ $events->url }}@endisset">
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="textTab" role="tabpanel" aria-labelledby="text_tab">
                            <div class="row mb-4 mx-0">
                                <label for="text">Краткий текст</label>
                                <div class="">
                                    <textarea name="short" id="short" class="form-control tinymce-editor" rows="14" placeholder="Краткий текст">@isset($events){{ $events->short }}@endisset</textarea>
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="text">Подробный текст</label>
                                <div class="">
                                    <textarea name="text" id="text" class="form-control tinymce-editor" rows="20" placeholder="Подробный текст">@isset($events){{ $events->text }}@endisset</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="lectorTab" role="tabpanel" aria-labelledby="lector_tab">
                            <div class="row mb-4 mx-0">
                                <div id="app_event_person">
                                    <event-person @if(isset($events))v-bind:id='{{ $events->id }}'@endif></event-person>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="sliderTab" role="tabpanel" aria-labelledby="slider_tab">
                            <div class="row mb-4 mx-0">
                                <div class="form-check form-switch d-flex ps-1 mt-3">
                                    <label class="form-check-label ms-2" for="slider_in">Добавить в слайдер</label>
                                    <input class="form-check-input form-check-green-input ms-3" type="checkbox" name="slider_in" id="slider_in" @if(isset($events))@if($events->slider_in) checked @endif @else checked @endif>
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="subtitle">Подзаголовок в слайдере</label>
                                <div class="textarea-group">
                                    <textarea name="subtitle_slider" id="subtitle_slider" class="form-control tinymce-editor-simple" rows="2" placeholder="Подзаголовок в слайдере">
                                        @isset($events){{ $events->subtitle_slider }}@endisset
                                    </textarea>
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="slider_text">Текст для слайдера</label>
                                <div class="textarea-group">
                                    <textarea name="slider_text" id="slider_text" class="form-control tinymce-editor-simple" rows="2" placeholder="Текст для слайдера">
                                        @isset($events){{ $events->slider_text }}@endisset
                                    </textarea>
                                </div>
                            </div>
                            <div class="row mb-4 mx-0">
                                <label for="image">@if(isset($events->image)){{ __('Изображение (для слайдера)') }} @else {{ __('Загрузить изображение (для слайдера)') }} @endif</label>
                                <div class="input-group">
                                    <input type="file" name="image" id="image" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="videoTab" role="tabpanel" aria-labelledby="video_tab">
                            <div class="row mx-0">
                                <div id="app_event_video">
                                    <event-video @if(isset($events))v-bind:event-id='{{ $events->id }}'@endif></event-video>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="fileTab" role="tabpanel" aria-labelledby="file_tab">
                            <div class="row mx-0">
                                <div id="app_event_file">
                                    <event-file @if(isset($events))v-bind:event-id='{{ $events->id }}'@endif></event-file>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="periodTab" role="tabpanel" aria-labelledby="period_tab">
                            <div class="row mx-0">
                                <div id="app_event_period">
                                    <event-periods @if(isset($events))v-bind:event-id='{{ $events->id }}'@endif></event-periods>
                                </div>
                            </div>
                        </div>

                    </div>

            </div>
        </div>
    </div>
</div>
</div>
</form>
<script>
    function checkInp(e, id){
        let inp = document.getElementById(id);
        inp.disabled = e.checked === true;
    }
    // function checkBackground(e){
    //     let inp = document.getElementById('background');
    //     if(e.checked == true)
    //         inp.disabled = true;
    //     else
    //         inp.disabled = false;
    // }
</script>
