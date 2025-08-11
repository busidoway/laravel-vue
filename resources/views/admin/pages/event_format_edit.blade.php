@extends('admin.template.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        @includeif('admin.includes.nav_page', ['title' => 'Редактирование', 'title_nav' => 'Форматы мероприятий', 'title_nav_route' => 'admin.event_format'])
    </div>
    @include('admin.modules.admin_event_format_edit')
@endsection