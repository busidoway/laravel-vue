@extends('template.main')

@section('meta-title')Информация о наборе групп на обучение и повышение квалификации@endsection

@section('content')
    @includeif('includes.page_title', ['page_title' => 'Информация о наборе групп на обучение и повышение квалификации'])
    <div class="container py-5">
        <div id="app_programs">
            <router-view></router-view>
        </div>
        @include('modules.modals.modal_program_app')
    </div>
    <style>
        .page-title h1 {
            font-size: 1.7rem;
        }
    </style>
@endsection
