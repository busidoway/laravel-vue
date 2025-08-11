{{-- Страница мероприятий --}}

@extends('template.main')

@section('meta-title')Видеоархив@endsection

@section('content')
    @includeif('includes.page_title', ['page_title' => 'Видеоархив'])
    <div class="container py-5">
        @include('modules.video_filter')
        @include('modules.events_video')
    </div>
@endsection