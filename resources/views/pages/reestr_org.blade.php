{{-- Страница реестра --}}

@extends('template.main')

@section('meta-title')Реестр аккредитованных образовательных организаций@endsection

@section('content')
    @includeif('includes.page_title', ['page_title' => 'Реестр аккредитованных образовательных организаций'])
    @includeif('modules.reestr_org_search')
    @includeif('modules.reestr_org')
@endsection