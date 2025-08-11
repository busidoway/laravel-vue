<div class="modal fade form-modal" id="formRegModal"  aria-hidden="true">
    <div class="tab-pane modal-content modal-dialog modal_form px-4 py-5">
        <form class="needs-validation form_feedback mb-0" id="form_feedback" method="post" autocomplete="off">
            @csrf
            <div class="modal-header border-0 mb-0 px-3">
                <div class="h5 col-12 fw-bold mb-0 p-0">Регистрация на мероприятие</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" name="header" value="Регистрация на форум">
            <!-- <input type="hidden" name="title"> -->
            <input type="hidden" name="date">
            <input type="hidden" name="license" value="1">
            <div class="mb-3 justify-content-center">
                <div class="col-12 mb-2">
                    <input type="text" name="last_name" class="form-control" placeholder="Фамилия*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2">
                    <input type="text" name="first_name" class="form-control" placeholder="Имя*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2">
                    <input type="text" name="middle_name" class="form-control" placeholder="Отчество*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2">
                    <input type="text" name="field_req[Учебное заведение]" class="form-control" placeholder="Учебное заведение*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2">
                    <input type="text" name="field_req[Факультет]" class="form-control" placeholder="Факультет*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2">
                    <input type="text" name="field_req[Курс]" class="form-control" placeholder="Курс*" autocomplete="off" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2">
                    <input type="text" name="field_req[Контактный номер]" class="form-control mask-input" placeholder="Номер телефона*" autocomplete="chrome-off" maxlength="16" required>
                    <div class="invalid-feedback">Обязательное поле</div>
                </div>
                <div class="col-12 mb-2">
                    <input type="text" name="email" class="form-control" placeholder="E-mail*" autocomplete="off" required>
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
            <div class="g-recaptcha d-flex justify-content-center my-3" id="recaptcha_forum_reg"></div>
            <div class="text-danger" id="recaptchaError"></div>
            <div class="d-flex mb-0 justify-content-center">
                <button class="btn btn-primary send_form" type="submit">Зарегистрироваться</button>
            </div>
        </form>
    </div>
</div>
