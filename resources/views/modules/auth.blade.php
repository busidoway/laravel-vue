@guest
    @if (Route::has('login'))
        <div class="">
            <a class="" href="{{ route('login') }}">{{ __('Login') }}</a>
        </div>
    @endif

@else
    <div class="card mt-5">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <a class="h5" href="#">
                        {{ Auth::user()->name }}
                    </a>
                </div>
                <div class="d-flex align-items-center">
                    <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Выйти') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            @if(Auth::user()->isAdmin())
            <div class="d-flex justify-content-between py-5">
                <a class="btn btn-outline-primary" href="{{ route('admin') }}" onclick="event.preventDefault(); document.getElementById('open-admin').submit();">
                    {{ __('Открыть админ-панель') }}
                </a>
                <form id="open-admin" action="{{ route('admin') }}" method="GET" class="d-none">
                    @csrf
                </form>
            </div>
            @endif
        </div>
    </div>
@endguest