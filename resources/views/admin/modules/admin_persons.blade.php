<div class="card card-body border-0 shadow table-wrapper table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="border-gray-200">#</th>
                <th class="border-gray-200">Имя</th>
                <th class="border-gray-200">Должность</th>
                <th class="border-gray-200"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($persons as $person)
            <!-- Item -->
            <tr>
                <td>
                    <a href="#" class="fw-bold">
                        {{ $person->id }}
                    </a>
                </td>
                <td>
                    <span class="fw-normal">{{ $person->last_name }} {{ $person->name }} {{ $person->middle_name }}</span>
                </td>
                <td><span class="fw-normal">{{ $person->position_short }} @if($person->position_length > 100)...@endif</span></td>
                <td>
                    <div class="btn-group">
                            <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="icon icon-sm">
                                    <span class="fas fa-ellipsis-h icon-dark"></span>
                                </span>
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu py-0">
                                <a class="dropdown-item" href="{{ route('persons.edit', $person->id) }}"><span class="fas fa-edit me-2"></span>Редактировать</a>
                                <button type="button" class="dropdown-item text-danger rounded-bottom" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $person->id }}"><span class="fas fa-trash-alt me-2"></span>Удалить</button>
                            </div>
                    </div>
                    @includeif('admin.modules.modal_delete', ['id' => $person->id, 'form_action' => route('persons.destroy', $person->id)])
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
        {{ $persons->onEachSide(0)->links() }}
    </div>
</div>
