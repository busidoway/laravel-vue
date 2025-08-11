{{-- Страница новостей --}}

@extends('template.main')

@section('meta-title')Налоговый дайджест@endsection

@section('content')
    @includeif('includes.page_title', ['page_title' => 'Налоговый дайджест'])
    <div id="wrapper">
        <div class="news container mb-5 mt-5">
            @include('modules.news', ['news_cat' => 1])
        </div>
    </div>
@endsection
