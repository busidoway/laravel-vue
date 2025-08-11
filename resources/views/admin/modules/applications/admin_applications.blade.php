<div class="card card-body border-0 shadow table-wrapper table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="border-gray-200">#</th>
            <th class="border-gray-200">Заголовок</th>
            <th class="border-gray-200">Имя отправителя</th>
            <th class="border-gray-200">Email</th>
            <th class="border-gray-200">Телефон</th>
            <th class="border-gray-200">Дата отправки</th>
            <th class="border-gray-200">Статус</th>
            <th class="border-gray-200"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($applications as $app)
            <!-- Item -->
            <tr>
                <td>
                    <a href="#" class="fw-bold">
                        {{ $app->id }}
                    </a>
                </td>
                <td>
                    <span class="fw-normal" title="{{ $app->title }}">{{ mb_strimwidth(strip_tags($app->title),0,80,'...') }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ $app->name_sender }} {{ $app->middle_name_sender }} {{ $app->last_name_sender }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ $app->email_sender }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ $app->phone_sender }}</span>
                </td>
                <td><span class="fw-normal">{{ Carbon\Carbon::parse($app->date_send)->format('d.m.Y H:i:s') }}</span></td>
                <td>
                    @if($app->status === 1)
                    <span class="fw-normal text-success">Отправлено</span>
                    @elseif($app->status === 0)
                    <span class="fw-normal text-danger">Ошибка отправки</span>
                    @endif
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
                            <a class="dropdown-item" href="{{ route('applications.edit', $app->id) }}"><span class="fas fa-edit me-2"></span>Редактировать</a>
                            <button type="button" class="dropdown-item text-danger rounded-bottom" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $app->id }}"><span class="fas fa-trash-alt me-2"></span>Удалить</button>
                        </div>
                    </div>
                    @includeif('admin.modules.modal_delete', ['id' => $app->id, 'form_action' => route('applications.destroy', $app->id)])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
        {{ $applications->onEachSide(0)->links() }}
    </div>
</div>
