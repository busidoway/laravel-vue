{{-- Точка зрения --}}

@extends('template.main')

@section('meta-title')Точка зрения@endsection

@section('content')
    @includeif('includes.page_title', ['page_title' => 'Точка зрения'])
    <div class="container py-5">
        <div class="text-justify mb-6">
        </div>
        @include('modules.viewpoints')
    </div>
@endsection
