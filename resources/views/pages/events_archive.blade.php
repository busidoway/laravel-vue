{{-- Страница архива мероприятий --}}

@extends('template.main')

@section('meta-title')Архив мероприятий@endsection

@section('content')
    @includeif('includes.page_title', ['page_title' => 'Архив мероприятий'])
    <div class="container py-5">
        <div class="d-flex mt-4 mb-4">
            <a href="{{ route('events') }}" class="btn btn-white">Назад</a>
        </div>
        @include('modules.events', ['archive' => true])
    </div>
@endsection