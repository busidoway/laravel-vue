    <div id="top" class="d-lg-none">
        <div class="container">
            <div class="row align-items-center">
                <div class="col py-2 d-flex justify-content-between align-items-center">
                    <div class="header-phone">
                        <a class="phone px-2" href="">
                            <span class="display-3" style="float: right; white-space: nowrap" ></span>
                        </a>
                        <a class="phone px-2" href="tel:+7 (999) 000-00-00">
                            <span class="display-3" style="float: right; white-space: nowrap" >+7 (999) 000-00-00</span>
                        </a>
                    </div>
                    <a class="phone px-2" href="mailto:info@mail.ru">
                        <span class="display-3" style="float: left; white-space: nowrap" >info@mail.ru</span>
                    </a>
                    <a class="phone px-2" href="">
                        <span class="display-3" style="float: left; white-space: nowrap"><i class="fab fa-telegram-plane"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <header id="header">
        <div class="container header_container">
            <div class="row mx-0 align-items-center">
                <a class="logo d-flex align-items-center" id="logo" href="/">
                    <img src="/images/logo159-90.png" class="img-logo" title="Компания" alt="Компания">
                    <span class="h6 ml-1 ml-lg-2 mb-0 logo-title"><span>Компания</span></span>
                </a>
                <div class="header-info d-flex align-items-center justify-content-end">
                    <div class="header-phone">
                        <div class="header-phone__icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="header-phone__items">
                            <a class="mr-1 mr-sm-1 mr-lg-3 d-none d-lg-inline-block phone" href="tel:+7 (999) 000-00-00">
                                <span class="display-3">+7 (999) 000-00-00</span>
                            </a>
                            <a class="mr-1 mr-sm-1 mr-lg-3 d-none d-lg-inline-block phone" href="tel:+7 (999) 000-00-00">
                                <span class="display-3">+7 (999) 000-00-00</span>
                            </a>
                        </div>
                    </div>
					<a class="mr-1 mr-sm-1 mr-lg-3 d-none d-lg-flex align-items-center phone" href="mailto:info@mail.ru">
                        <i class="far fa-envelope"></i>
                        <span class="display-3">info@mail.ru</span>
                    </a>
                    <a class="mr-1 mr-sm-1 mr-lg-2 d-none d-lg-inline-block header-icon header-icon-telegram" href="">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                    <a
                        tabindex="0"
                        class="mr-1 mr-sm-2 header-icon header-icon-contact"
                        href="#"
                        data-bs-container="body"
                        data-bs-toggle="popover"
                        data-bs-trigger="focus"
                        data-bs-placement="bottom"
                        onclick="event.preventDefault()"
                        data-bs-content='<b>Адрес офиса:</b><br>
                        г. Москва<br>
                        <br>
                        <b>Время работы:</b><br>
                        с пн. по чт. с 10-00 до 19-00,<br>
                        в пт. с 10-00 до 17-00<br>
                        <br>
                        <a href=&quot;/contacts&quot; class=&quot;btn btn-white&quot;>Контакты</a>'>
{{--                            <i class="fa-solid fa-location-dot"></i>--}}
{{--                        <i class="fas fa-map-marker-alt"></i>--}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#990606" class="bi bi-geo-alt" viewBox="0 0 16 16">
                            <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"></path>
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
                        </svg>
                    </a>
                    @if(Auth::check())
                    <a class="d-lg-inline-block user-auth header-icon header-icon-user" href="{{ route('auth') }}">
                        <i class="fa-regular fa-user"></i>
                    </a>
                    @else
                    <a class="d-lg-inline-block header-icon header-icon-user" href="#" data-bs-toggle="modal" data-bs-target="#modalAuth">
                        <i class="fa-regular fa-user"></i>
                    </a>
                    @endif
                    <a id="mmenu-toggler" aria-label="Меню" class="d-lg-none mburger mburger--squeeze" href="#mmenu" title="Меню"><b></b><b></b><b></b></a>
                </div>
            </div>
        </div>
    </header>
