@php
//$event_categories = DB::table('event_categories')->get();
@endphp
<div class="video-filter d-flex tab-content" id="filter-tabs-content" style="">
    <form action="{{ route('events_video') }}" class="form-inline" method="get">
        @csrf
        <div class="row no-gutters align-items-center mb-2">
            <div class="video-filter-item col-md-6 col-lg-5 p-1 mb-1">
                <input type="text" name="title" class="form-control" placeholder="Название" value="@isset($search->title){{ $search->title }}@endisset">
            </div>
            <div class="video-filter-item col-md-6 col-lg-7 d-flex align-items-center p-1 mb-1">
                <span class="me-2 text-nowrap">Дата от:</span>
                <input type="text" name="date_from" class="form-control datepicker-here" autocomplete="off" value="@isset($search->date_from){{ $search->date_from }}@endisset">
                <span class="mx-2">до:</span>
                <input type="text" name="date_to" class="form-control datepicker-here" autocomplete="off" value="@isset($search->date_to){{ $search->date_to }}@endisset">
            </div>
            <div class="video-filter-item col-md-6 col-lg-3 p-1">
                <select class="form-select" name="person">
                    <option value="">-- Лектор --</option>
                    @isset($persons)
                        @foreach($persons as $p)
                            <option value="{{ $p->id }}" @if(isset($search->person) && $search->person == $p->id)selected @endif>{{ $p->last_name }} {{ $p->name }} {{ $p->middle_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="video-filter-item col-md-6 col-lg-3 p-1">
                <select class="form-select" name="cat">
                    <option value="">-- Категория --</option>
                    @isset($event_categories)
                    @foreach($event_categories as $cat)
                    <option value="{{ $cat->id }}" @if(isset($search->cat) && $search->cat == $cat->id)selected @endif>{{ $cat->title }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>
            <div class="video-filter-item col-md-6 col-lg-3 p-1">
                <select class="form-select" name="">
                    <option>-- Тематика --</option>
                </select>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col px-1">
                <button type="submit" class="btn btn-white">Применить</button>
            </div>
            <div class="col px-1">
                <a href="{{ route('events_video') }}" class="btn btn-white">Сбросить</a>
            </div>
        </div>
    </form>
</div>
<style>
    .video-filter {
        background-color: #f2f2f2;
        border-radius: 10px;
        padding:20px;
    }
</style>