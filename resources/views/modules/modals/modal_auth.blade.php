<div class="modal fade modal-auth" id="modalAuth" tabindex="-1" aria-labelledby="modalAuthLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="modal-header border-0 d-flex justify-content-center">
            <h4 class="modal-title text-center">Авторизация</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body border-0">
              <div class="mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail">
              </div>
              <div class="">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Пароль">
              </div>
          </div>
          <div class="modal-footer border-0 d-flex justify-content-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Запомнить') }}
                </label>
            </div>
            <button type="submit" class="btn btn-primary ms-5">Войти</button>
          </div>
      </form>
    </div>
  </div>
</div>