<div class="slider-item__date-content">
    @isset($date)
    <div class="slider-item__date">
        <span class="slider-item__date-title">Дата:</span>
        <span class="slider-item__date-text">{{ $date }}</span>
    </div>
    @endisset
    @isset($time)
    <div class="slider-item__time">
        <span class="slider-item__date-title">Время:</span>
        <span class="slider-item__date-text">{!! $time !!}</span>
    </div>
    @endisset
</div>
