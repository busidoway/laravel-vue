<template>
    <div class="modal fade form-modal modal-event" id="modalProgramApp"  aria-hidden="true">
        <div class="tab-pane modal-content modal-dialog modal_form px-4 py-5">
            <div class="modal-header border-0 mb-0 px-3">
                <div class="h5 col-12 fw-bold p-0">Заявка</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="mb-3 col-12 fs-5 form__card-title"></div>
            <div class="mb-3 justify-content-center">
                <div class="col-12 mb-2 mb-2">
                    <input type="text" name="last_name" class="form-control" placeholder="Фамилия*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-2">
                    <input type="text" name="first_name" class="form-control" placeholder="Имя*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-2">
                    <input type="text" name="middle_name" class="form-control" placeholder="Отчество*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-2">
                    <input type="text" name="email" class="form-control" placeholder="E-mail*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-2">
                    <input type="text" name="field_req[Контактный номер]" class="form-control mask-input" placeholder="Контактный номер*" autocomplete="off" maxlength="16" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-2">
                    <input type="text" name="field_req[Город]" class="form-control" placeholder="Город*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-2">
                    <input type="text" name="field_req[Образование]" class="form-control" placeholder="Образование*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-2">
                    <input type="text" name="field_req[Должность]" class="form-control" placeholder="Должность*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
            </div>
            <div class="g-recaptcha d-flex justify-content-center my-3" id="recaptcha_event"></div>
            <div class="text-danger" id="recaptchaError"></div>
            <div class="d-flex mb-4 justify-content-center">
                <button class="btn btn-primary send_form" type="submit" id="send_form">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Зарегистрироваться
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>
