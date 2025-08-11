<div class="modal fade" id="modal-delete-{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ $form_action }}" method="post">
                @csrf
                <div class="modal-header">
                    <h2 class="h6 modal-title">Удалить выбранную позицию?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить</button>
                        <button type="button" class="btn btn-gray-200 text-gray-600 ms-auto" data-bs-dismiss="modal">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</div>