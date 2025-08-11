<section class="jumbotron jumbotron-fluid position-relative asd main-slider" id="slideshow">
    <div class="owl-carousel owl-theme" data-dots="true" data-nav="true" data-drag="true" data-nav-container=".owl-nav-buttons" data-items="1">
        @php
            date_default_timezone_set("Europe/Moscow");
            $curr_date = date('Y-m-d H:i:s');

            $event_slider = $events['slider'];

        @endphp
        @foreach($slider as $s)
            @php
                $img_full = 0;
                if (isset($s->image) && (isset($s->img_full) && $s->img_full !== 0)) {
                    $img_full = 1;
                }
            @endphp
        <div class="slider-item slider-item-{{ $s->id }} @if($img_full === 1) img-full @endif">
{{--            <div class="slider-item__img" style="background-color:#f2f2f2"></div>--}}
            <div class="container container-slider">
                <div class="h-100">
                    <div class="slider-item__content">
                        <div class="slider-item__section">
                            @isset($s->title)
                                <div class="slider-item__title slider-item__item">{!! $s->title !!}</div>
                            @endisset

                            @isset($s->text1)
                                <div class="slider-item__text slider-item__item">{!! $s->text1 !!}</div>
                            @endisset

                            @isset($s->text2)
                                <div class="slider-item__text slider-item__item">{!! $s->text2 !!}</div>
                            @endisset

                            @isset($s->text3)
                                <div class="slider-item__text slider-item__item">{!! $s->text3 !!}</div>
                            @endisset

                            @isset($s->image)
                                <div class="slide-image">
                                    <img src="{{ $s->image }}" alt="">
                                </div>
                            @endisset

                        </div>

                        @isset($s->url)
                        <div class="slider-item__bottom">
                            <div class="slider-item__button-content">
                                <a href="{{ $s->url }}" id="btn_slider" class="btn btn-md btn-atknin-white">Подробнее</a>
                            </div>
                        </div>
                        @endisset

                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @foreach($event_slider as $es)
            <div class="slider-item">
{{--                <div class="slider-item__img" style="background-color:#f2f2f2"></div>--}}
                <div class="container container-slider">
                    <div class="h-100">
                        <div class="slider-item__content">
                            <div class="slider-item__top">
                                <div class="slider-item__section">
                                    @isset($es->event_title)
                                        <div class="slider-item__title slider-item__item">{{ strip_tags($es->event_title) }}</div>
                                    @endisset

                                    @isset($es->subtitle_slider,)
                                        <div class="slider-item__subtitle slider-item__item">{!! $es->subtitle_slider !!}</div>
                                    @endisset

                                    @isset($es->slider_text)
                                        <div class="slider-item__text slider-item__item">{!! $es->slider_text !!}</div>
                                    @endisset

                                    @if(isset($es->date_list) || isset($es->date_public))
                                        <div class="slider-item__date-time slider-item__item">
                                            @if (isset($es->date_list))
                                                @php $date_list = explode(",", $es->date_list); @endphp
                                                @include('modules.components.slider_date_time', ['date' => getDateRus($date_list), 'time' => $es->time])
                                            @elseif(isset($es->date_public))
                                                @if(isset($es->date_end))
                                                    @include('modules.components.slider_date_time', ['date' => getDateRus($es->date_public, $es->date_end), 'time' => $es->time])
                                                @else
                                                    @include('modules.components.slider_date_time', ['date' => getDateRus($es->date_public), 'time' => $es->time])
                                                @endif
                                            @endif
                                        </div>
                                    @endif

                                    @if(isset($es->format))
                                        <div class="slider-item__format slider-item__item">
                                            <span class="slider-item__format-title">Формат:</span>
                                            <span class="slider-item__format-text">{!! $es->format !!}</span>
                                        </div>
                                    @elseif(isset($es->format_title))
                                        <div class="slider-item__format slider-item__item">
                                            <span class="slider-item__format-title">Формат:</span>
                                            <span class="slider-item__format-text">{!! $es->format_title !!}</span>
                                        </div>
                                    @endif

                                </div>

                            </div>
                            <div class="slider-item__bottom">
                                <div class="slider-item__button-content">
                                    <a href="/events/{{ $es->event_id }}" id="btn_slider" class="btn btn-md btn-atknin-white">Подробнее</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


    </div>

    <div class="owl-nav-container">
        <div class="owl-dots"></div>
        <div class="owl-arrows">
            <div class="owl-nav-buttons__left"></div>
            <div class="owl-nav-buttons__right"></div>
        </div>
    </div>
</section>

@include('modules.events_modal')
