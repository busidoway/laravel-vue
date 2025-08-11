@php 
date_default_timezone_set("Europe/Moscow");
$curr_date = date('Y-m-d H:i:s');

$user = Auth::user();
@endphp
<div class="events calendar-dates events-page-container events-page">
    <div class="row mb-3 filter-items">
        @foreach($events_video as $event)
            @php if($user) $event_access = getEventVideoAccess($event->id, $user->id); @endphp
            {{-- @if($event->date_end < $curr_date) --}}
            <div class="col-12 col-sm-12 col-md-6 сol-lg-4 col-xl-4 filter-new mb-5 event-item event-item-{{ $event->id }} @if (isset($event->cat_code))cat-code-{{ $event->cat_code }}@endif" data-index="{{ $loop->iteration }}">
                <div class="card h-100">
                {{-- $event_access --}}
                    <div class="card-header">
                        @if (isset($event->cat_title)) 
                        <span class="">{{ $event->cat_title }}</span>
                        @endif
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column">
                        <div class="card-body-header d-flex justify-content-between flex-column h-100">
                            <div class="header-link" title="{!! $event->title !!}">
                                <div class="h5 card-title mb-3 fw-bold">{!! $event->title !!}</div>
                                @isset($event->short)<div class="card-body-short mb-3">{!! $event->short !!}</div>@endisset
                            </div>
                            <div class="card-body-date">
                                <div class="card-price mb-4">
                                    @php $event_price = (int)$event->price; @endphp
                                    <div class="">Стоимость – {!! number_format($event_price, 0, '', ' ') !!} руб.</div>
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
                        @endforeach   
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between mb-2">
                        @if(Auth::check() && $event_access)
                        <a href="{{ route('event_video_view', ['id' => $event->id]) }}" class="btn btn-white">Смотреть</a>
                        @else
                        <a href="#" class="btn btn-primary btn-modal" data-recaptcha-id="recaptcha_event" data-bs-toggle="modal" data-bs-target="#formEventModal">Купить</a> 
                        @endif
                        <a @if(isset($event->url)) href="{{ $event->url }}" @else href="{{ route('event_video_inner', ['id' => $event->id]) }}" @endif class="btn btn-white">Подробнее</a>
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        @endforeach 
    </div>
    <div class="paginator pagination-container d-flex justify-content-center mt-2">
        {{ $events_video->onEachSide(1)->links() }}
    </div>
    @include('modules.events_video_modal')
</div>
<style>
    
</style>