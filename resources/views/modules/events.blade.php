@php
if(isset($archive)) $events_data = $events['events_archive']; else $events_data = $events['events'];
date_default_timezone_set("Europe/Moscow");
$curr_date = date('Y-m-d H:i:s');
@endphp
<div class="events calendar-dates events-page-container events-page">
    <div class="row mb-3 filter-items">
        @foreach($events_data as $event)
            <div class="col-12 col-sm-12 col-md-6 сol-lg-4 col-xl-4 filter-new mb-5 event-item event-item-{{ $event->id }} @if (isset($event->cat_code))cat-code-{{ $event->cat_code }}@endif" data-index="{{ $loop->iteration }}">
                <div class="card h-100">
                    <div class="card-header" data-cat="@isset($event->cat_code){{ $event->cat_code }}@endisset">
                        @if (isset($event->cat_title))
                        <span class="">{{ $event->cat_title }}</span>
                        @endif
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column">
                        <div class="card-body-header d-flex justify-content-between flex-column h-100">
                            <div class="header-link" title="{!! $event->title !!}">
                                <div class="h5 card-title mb-3 fw-bold">{!! $event->title !!}</div>
                                <div class="card-subtitle">{!! $event->subtitle !!}</div>
                                @isset($event->short)<div class="card-body-short mb-3">{!! $event->short !!}</div>@endisset
                            </div>
                            <div class="card-body-date">
                                <div class="card-price mb-4" data-price="@isset($event->price_m){{ $event->price_m }}@endisset">
                                    @if($event->cat_code == 'blitz-seminar')
                                        <div class="">{!! $event->price_text !!}</div>
                                    @else
                                        @php $event_price = (int)$event->price; @endphp
                                        @if($event_price == 0 && $event->price_m == 0)
                                        <div class="text-fpnk">Участие бесплатно</div>
                                        @else
                                        <div class="">Стоимость – {!! number_format($event_price, 0, '', ' ') !!} руб.</div>
                                        @isset($event->price_m)
                                            @if($event->price_m === 0)
                                            <div class="text-fpnk">Для членов бесплатно</div>
                                            @else
                                            <div class="">Для членов – {!! number_format($event->price_m, 0, '', ' ') !!} руб.</div>
                                            @endif
                                        @endisset
                                        @endif
                                    @endif
                                </div>
                                <div class="media align-items-center event-date" data-date="@isset($event->date_public){{ getDateRus($event->date_public) }}@endisset" data-time="@isset($event->time){{ $event->time }}@endisset">
                                    <i class="icon icon-calendar icon-4x mr-4"></i>
                                    <div class="media-body">
                                        @if($event->cat_code == 'blitz-seminar')
                                            @php
                                                $date_time = explode(";", $event->time);
                                            @endphp
                                            <p class="my-0 event-media-date">{{ getDateRus($date_time[0]) }}</p>
                                            <p class="my-0 event-media-day">{{ getDayRus($date_time[0]) }}</p>
                                            <p class="my-0 event-media-time">{!! $date_time[1] !!}</p>
                                        @else
                                            @if(isset($event->date_list))
                                                @php $date_list = explode(",", $event->date_list); @endphp
                                                <p class="my-0 event-media-date">{!! getDateRus($date_list, null, 1) !!}</p>
                                            @elseif(isset($event->date_public))
                                                @if(isset($event->date_end))
                                                <p class="my-0 event-media-date">{!! getDateRus($event->date_public, $event->date_end, 1) !!}</p>
                                                @else
                                                <p class="my-0 event-media-date">{{ getDateRus($event->date_public) }}</p>
                                                <p class="my-0 event-media-day">{{ getDayRus($event->date_public) }}</p>
                                                @endif
                                            @endif
                                            @if($event->cat_code !== 'videokurs')
                                            <p class="my-0 event-media-time">{!! $event->time !!}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php $event_people = getPeopleEvent($event->id); $count_people = count($event_people); @endphp
                        <div class="media @if($count_people < 2) flex-column card-body-person @else d-grid flex-wrap card-body-person card-body-person-items align-items-start @endif mt-4 mb-3 pt-3" style="border-top:2px solid #d9d9d9;">
                        @foreach ($event_people as $people)
                            @php
                                $people_name = get_initial($people->name);
                                $people_middle_name = get_initial($people->middle_name);
                            @endphp
                            <div class="person-item d-flex @if($count_people > 1)flex-column justify-content-center @else align-items-center @endif">
                                @isset($people->img)<span class="media-avatar mr-4 rounded-circle" style="background-image: url('{{ asset($people->img) }}')"></span>@endisset
                                <div class="media-body">
                                    @if($count_people < 2)
                                    <div class="mb-2">Спикер:</div>
                                    @endif
                                    <h6 class="h6 fw-bold m-0 person-name">{{ $people->last_name }} {{ $people_name }}{{ $people_middle_name }}</h6>
                                </div>
                            </div>
                            @if($count_people < 2)
                                @if($people->position_visible == 1)<div class="person-position py-1 mb-2">{{ $people->position }}</div>@endif
                            @endif
                            {{-- @if($count_people > 1)</div>@endif --}}
                        @endforeach
                        </div>
                    </div>

                    @if($event->date_public >= $curr_date || $event->date_end >= $curr_date || $event->date_public == null)
                    <div class="card-footer d-flex justify-content-between mb-2">
                        @if($event->cat_code != 'blitz-seminar')
                        <a href="#" class="btn btn-primary btn-modal" data-event="1" data-id="{{ $event->id }}" data-recaptcha-id="recaptcha_event" data-bs-toggle="modal" data-bs-target="#formEventModal">Записаться</a>
                        @endif
                        <a @if(isset($event->url)) href="{{ $event->url }}" @else href="{{ route('event_inner', ['id' => $event->id]) }}" @endif class="btn btn-white">Подробнее</a>
                    </div>

                    @else
                    <div class="card-footer d-flex @if(($event->text != '' && $event->text != 'NULL') || ($event->url != '' && $event->url != 'NULL'))justify-content-between @else justify-content-end @endif mb-2">
                        @if(($event->text != '' && $event->text != 'NULL') || (isset($event->url)))
                        <a @if(isset($event->url)) href="{{ $event->url }}" @else href="{{ route('event_inner', ['id' => $event->id]) }}" @endif class="btn btn-white">Подробнее</a>
                        @endif
                        <span class="btn btn-gray">Завершен</span>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @isset($archive)
    <div class="paginator pagination-container d-flex justify-content-center mt-2">
        {{ $events_data->onEachSide(1)->links() }}
    </div>
    @endisset
        @include('modules.events_modal')
</div>
<style>

</style>
