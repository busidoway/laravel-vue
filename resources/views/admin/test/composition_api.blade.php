@extends('admin.template.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        @includeif('admin.includes.nav_page', ['title' => 'Composition API'])
    </div>
    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <div id="app_test_composition_api">
            <composition-api></composition-api>
        </div>
    </div>
@endsection
