<div class="card card-body border-0 shadow table-wrapper table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="border-gray-200">#</th>
                <th class="border-gray-200">Заголовок</th>					
                <th class="border-gray-200">Дата</th>
                <th class="border-gray-200 text-center">Сортировка</th>
                <th class="border-gray-200"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($slider as $s)
            <!-- Item -->
            <tr>
                <td> 
                    <a href="#" class="fw-bold">
                        {{ $s->sort }}
                    </a>
                </td> 
                <td>
                    <span class="fw-normal">{{ $s->title }}</span>
                </td>
                <td><span class="fw-normal">@isset($s->date){{ Carbon\Carbon::parse($s->date)->format('d.m.Y') }}@endisset</span></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <span class="fw-normal">
                                <div class="btn-group-vertical" role="group">
                                    <form action="{{ route('admin.slider_sort_up', $s->id) }}" method="post">
                                        @csrf
                                        <button type="submit" name="up" class="btn btn-sm btn-outline-primary mb-1 lh-1" title="Вверх" style="padding: 4px 10px 0px 10px; border-bottom-left-radius:0; border-bottom-right-radius:0;"><i class="fas fa-sort-up"></i></button>
                                    </form>
                                    <form action="{{ route('admin.slider_sort_down', $s->id) }}" method="post">
                                        @csrf
                                        <button type="submit" name="down" class="btn btn-sm btn-outline-primary lh-1" title="Вниз" style="padding: 0px 10px 4px 10px; border-top-left-radius:0; border-top-right-radius:0;"><i class="fas fa-sort-down"></i></button>
                                    </form>
                                </div>
                        </span>
                    </div>
                </td>
                <td>
                    <div class="btn-group">
                            <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="icon icon-sm">
                                    <span class="fas fa-ellipsis-h icon-dark"></span>
                                </span>
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu py-0">
                                <a class="dropdown-item" href="{{ route('slider.edit', $s->id) }}"><span class="fas fa-edit me-2"></span>Редактировать</a>
                                <button type="button" class="dropdown-item text-danger rounded-bottom" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $s->id }}"><span class="fas fa-trash-alt me-2"></span>Удалить</button>
                            </div>
                    </div>
                    @includeif('admin.modules.modal_delete', ['id' => $s->id, 'form_action' => route('slider.destroy', $s->id)])
                </td>
            </tr>
            @endforeach                    
        </tbody>
    </table>
    <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
        {{ $slider->onEachSide(0)->links() }}
    </div>
</div>