<div class="card card-body border-0 shadow table-wrapper table-responsive">
    <table class="table table-new table-hover">
        <thead>
            <tr>
                <th class="border-gray-200">№ аттестата</th>
                <th class="border-gray-200">ФИО</th>						
                <th class="border-gray-200">Город</th>
                <th class="border-gray-200">Регион</th>
                <th class="border-gray-200">E-mail</th>
                <th class="border-gray-200">Дата выдачи аттестата</th>
                <th class="border-gray-200">Окончание действия аттестата</th>
                <th class="border-gray-200"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($reestr as $r)
            <!-- Item -->
            <tr>
                <td>
                    <a href="#" class="fw-bold">
                        {{ $r->num_doc }}
                    </a>
                </td>
                <td>
                    <span class="fw-normal">{{ $r->name }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ $r->city }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ $r->region }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ $r->email }}</span>
                </td>
                <td><span class="fw-normal">{{ Carbon\Carbon::parse($r->date_start)->format('d.m.Y') }}</span></td>
                <td><span class="fw-normal">{{ Carbon\Carbon::parse($r->date_end)->format('d.m.Y') }}</span></td>
                <td>
                    <div class="btn-group">
                            <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="icon icon-sm">
                                    <span class="fas fa-ellipsis-h icon-dark"></span>
                                </span>
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu py-0">
                                <a class="dropdown-item" href="{{ route('reestr.edit', $r->id) }}"><span class="fas fa-edit me-2"></span>Редактировать</a>
                                <button type="button" class="dropdown-item text-danger rounded-bottom" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $r->id }}"><span class="fas fa-trash-alt me-2"></span>Удалить</button>
                            </div>
                    </div>
                    @includeif('admin.modules.modal_delete', ['id' => $r->id, 'form_action' => route('reestr.destroy', $r->id)])
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
        {{ $reestr->onEachSide(0)->links() }}
    </div>
</div>
<style>
    .table-new td, .table-new th {
        white-space: normal;
    }
</style>