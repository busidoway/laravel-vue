<div class="modal fade form-modal" id="formExamModal"  aria-hidden="true">
    <div class="tab-pane modal-content modal-dialog modal_form px-4 py-5">
        <form class="needs-validation form_feedback" id="form_feedback" method="post" autocomplete="off">
            @csrf
            <div class="modal-header border-0 mb-0 px-3 py-2">
                <div class="h5 col-12 fw-bold p-0 m-0 text-center">Запись на экзамен</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="mb-3 col-12 fs-5 modal-header-title text-center"></div>
            <input type="hidden" name="header" value="Запись на экзамен">
            <input type="hidden" name="title">
            <input type="hidden" name="cat">
            <input type="hidden" name="price">
            <input type="hidden" name="date">
            <input type="hidden" name="time">
            <input type="hidden" name="license" value="1">
            <div class="mb-3 justify-content-center">
                <div class="col-12 mb-2 mb-4">
                    <input type="text" name="name" class="form-control" placeholder="Ф.И.О.*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-4">
                    <input type="text" name="email" class="form-control" placeholder="E-mail*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-4">
                    <input type="text" name="field_req[Контактный номер]" class="form-control mask-input" placeholder="Контактный номер*" autocomplete="off" maxlength="16" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-4">
                    <input type="text" name="field_req[Город]" class="form-control" placeholder="Город*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-4">
                    <textarea name="field_req[Образовательная организация]" class="form-control" placeholder="Образовательная организация, в которой обучались на налогового консультанта*" autocomplete="off" maxlength="16" required></textarea>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-4">
                    <input type="text" name="field_req[Год прохождения подготовки]" class="form-control" placeholder="Год прохождения подготовки*" autocomplete="off" maxlength="16" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2 mb-2">
                    <div class="check-license px-4">
                        <input type="checkbox" class="form-check-input" name="check_license" id="" required>
                        <span>
                            Нажимая кнопку «Зарегистрироваться», я принимаю условия <a href="/license/" target="_blank">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных
                        </span>
                    </div>
                </div>
            </div>
            <div class="g-recaptcha d-flex justify-content-center my-3" id="recaptcha_exam"></div>
            <div class="text-danger" id="recaptchaError"></div>
            <div class="d-flex mb-4 justify-content-center">
                <button class="btn btn-primary send_form" type="submit" id="send_form">Записаться</button>
            </div>
        </form>
    </div>
</div>
