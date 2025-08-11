<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="/">
        <img class="navbar-brand-dark" src="/assets/img/brand/light.svg" alt="Volt logo" /> <img class="navbar-brand-light" src="/assets/img/brand/dark.svg" alt="Volt logo" />
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
  <div class="sidebar-inner px-4 pt-3">
    <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
      <div class="d-flex align-items-center">
        <div class="d-block">
          <h2 class="h5 mb-3">{{ auth()->user()->name }}</h2>
          <a href="{{ route('logout') }}" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
            <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Выйти
          </a>
        </div>
      </div>
      <div class="collapse-close d-md-none">
        <a href="#sidebarMenu" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true"
            aria-label="Toggle navigation">
            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
          </a>
      </div>
    </div>
    <ul class="nav flex-column pt-3 pt-md-0">

        <li class="nav-item">
            <a href="{{ route('home') }}" target="_blank" class="nav-link d-flex align-items-center">
            <span class="sidebar-icon">
                <img src="/assets/img/brand/light.svg" height="20" width="20" alt="Volt Logo">
            </span>
            <span class="mt-1 ms-1 sidebar-text">Перейти на сайт</span>
            </a>
        </li>

        <li class="nav-item  {{ (Route::is('admin.applications') ? 'active' : '') }} ">
            <a href="{{ route('admin.applications') }}" class="nav-link">
            <span class="sidebar-icon">
                <i class="fas fa-envelope"></i>
            </span>
                <span class="sidebar-text">Заявки</span>
            </a>
        </li>

        <li class="nav-item  {{ (Route::is('admin.slider') ? 'active' : '') }} ">
            <a href="{{ route('admin.slider') }}" class="nav-link">
            <span class="sidebar-icon">
                <i class="fas fa-images"></i>
            </span>
            <span class="sidebar-text">Слайдер</span>
            </a>
        </li>

        <li class="nav-item  {{ (Route::is('admin.news') ? 'active' : '') }} ">
            <span class="nav-link {{ (Route::is('admin.news') || Route::is('admin.news_category') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-news">
                <span>
                    <span class="sidebar-icon">
                        <i class="far fa-newspaper"></i>
                    </span>
                    <span class="sidebar-text">Новости</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.news') || Route::is('admin.news_category')  ? 'show' : '') }} " role="list" id="submenu-news" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.news') ? 'active' : '') }}">
                        <a href="{{ route('admin.news') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.news_category') ? 'active' : '') }}">
                        <a href="{{ route('admin.news_category') }}" class="nav-link">
                            <span class="sidebar-text">Категории</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item  {{ (Route::is('admin.events') ? 'active' : '') }} ">
            <span class="nav-link {{ (Route::is('admin.events') || Route::is('admin.event_category') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-events">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <span class="sidebar-text">Мероприятия</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse
                {{
                    (
                        Route::is('admin.events') ||
                        Route::is('admin.event_categories') ||
                        Route::is('admin.event_sub_categories') ||
                        Route::is('admin.event_format')  ?
                        'show' : ''
                    )
                }} " role="list" id="submenu-events" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.events') ? 'active' : '') }}">
                        <a href="{{ route('admin.events') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.event_categories') ? 'active' : '') }}">
                        <a href="{{ route('admin.event_categories') }}" class="nav-link">
                            <span class="sidebar-text">Категории</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.event_sub_categories') ? 'active' : '') }}">
                        <a href="{{ route('admin.event_sub_categories') }}" class="nav-link">
                            <span class="sidebar-text">Дополнительные<br>категории</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.event_format') ? 'active' : '') }}">
                        <a href="{{ route('admin.event_format') }}" class="nav-link">
                            <span class="sidebar-text">Форматы</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <span class="nav-link
                {{
                    (Route::is('admin.programs_edu') ||
                    Route::is('admin.type_programs') ||
                    Route::is('admin.programs') ||
                    Route::is('admin.programs_groups') ||
                    Route::is('admin.programs_app') ||
                    Route::is('admin.form_education') ?
                    '' : 'collapsed')
                }}
                d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-programs">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-th-list"></i>
                    </span>
                    <span class="sidebar-text">Программы</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse
                {{
                    (Route::is('admin.programs_edu') ||
                    Route::is('admin.type_programs') ||
                    Route::is('admin.programs') ||
                    Route::is('admin.form_education') ||
                    Route::is('admin.programs_app') ||
                    Route::is('admin.programs_groups')  ?
                    'show' : '')
                }} "
                role="list" id="submenu-programs"
                aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.programs_edu') ? 'active' : '') }}">
                        <a href="{{ route('admin.programs_edu') }}" class="nav-link">
                            <span class="sidebar-text">Наборы программ</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.type_programs') ? 'active' : '') }}">
                        <a href="{{ route('admin.type_programs') }}" class="nav-link">
                            <span class="sidebar-text">Типы программ</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.programs_groups') ? 'active' : '') }}">
                        <a href="{{ route('admin.programs_groups') }}" class="nav-link">
                            <span class="sidebar-text">Группы программ</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.programs') ? 'active' : '') }}">
                        <a href="{{ route('admin.programs') }}" class="nav-link">
                            <span class="sidebar-text">Программы</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.form_education') ? 'active' : '') }}">
                        <a href="{{ route('admin.form_education') }}" class="nav-link">
                            <span class="sidebar-text">Формы обучения</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.programs_app') ? 'active' : '') }}">
                        <a href="{{ route('admin.programs_app') }}" class="nav-link">
                            <span class="sidebar-text">Заявки</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <span class="nav-link {{ (Route::is('admin.organizations') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-organizations">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-university"></i>
                    </span>
                    <span class="sidebar-text">Организации</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.organizations') ? 'show' : '') }} " role="list" id="submenu-organizations" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.organizations') ? 'active' : '') }}">
                        <a href="{{ route('admin.organizations') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <span class="nav-link {{ (Route::is('admin.cities') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-cities">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-city"></i>
                    </span>
                    <span class="sidebar-text">Города</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.cities') ? 'show' : '') }} " role="list" id="submenu-cities" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.cities') ? 'active' : '') }}">
                        <a href="{{ route('admin.cities') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <span class="nav-link {{ (Route::is('admin.exams') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-exams">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </span>
                    <span class="sidebar-text">Экзамены</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.exams') ? 'show' : '') }} " role="list" id="submenu-exams" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.exams') ? 'active' : '') }}">
                        <a href="{{ route('admin.exams') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item  {{ (Route::is('admin.viewpoints') ? 'active' : '') }} ">
            <a href="{{ route('admin.viewpoints') }}" class="nav-link">
            <span class="sidebar-icon">
                <i class="fas fa-calendar-day"></i>
            </span>
            <span class="sidebar-text">Точка зрения</span>
            </a>
        </li>
        <li class="nav-item">
            <span class="nav-link {{ (Route::is('admin.video') || Route::is('admin.video_load') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-video">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-film"></i>
                    </span>
                    <span class="sidebar-text">Видео</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.video') || Route::is('admin.video_load') || Route::is('admin.video_category')  ? 'show' : '') }} " role="list" id="submenu-video" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.video') ? 'active' : '') }}">
                        <a href="{{ route('admin.video') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.video_category') ? 'active' : '') }}">
                        <a href="{{ route('admin.video_category') }}" class="nav-link">
                            <span class="sidebar-text">Категории</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.video_load') ? 'active' : '') }}">
                        <a href="{{ route('admin.video_load') }}" class="nav-link">
                            <span class="sidebar-text">Загрузка</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <span class="nav-link {{ (Route::is('admin.reviews') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-reviews">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-comments"></i>
                    </span>
                    <span class="sidebar-text">Отзывы</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.reviews') || Route::is('admin.reviews_categories') ? 'show' : '') }} " role="list" id="submenu-reviews" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.reviews') ? 'active' : '') }}">
                        <a href="{{ route('admin.reviews') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.reviews_categories') ? 'active' : '') }}">
                        <a href="{{ route('admin.reviews_categories') }}" class="nav-link">
                            <span class="sidebar-text">Категории</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item  {{ (Route::is('admin.persons') ? 'active' : '') }} ">
            <a href="{{ route('admin.persons') }}" class="nav-link">
            <span class="sidebar-icon">
                <i class="fas fa-user-tie"></i>
            </span>
            <span class="sidebar-text">Сотрудники</span>
            </a>
        </li>

        <li class="nav-item  {{ (Route::is('admin.users') ? 'active' : '') }} ">
            <a href="{{ route('admin.users') }}" class="nav-link">
            <span class="sidebar-icon">
                <i class="fas fa-users"></i>
            </span>
            <span class="sidebar-text">Пользователи</span>
            </a>
        </li>

        <li class="nav-item">
            <span class="nav-link {{ (Route::is('admin.reestr') || Route::is('admin.reestr_load') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-reestr">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-list-alt"></i>
                    </span>
                    <span class="sidebar-text">Реестр</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.reestr') || Route::is('admin.reestr_load')  ? 'show' : '') }} " role="list" id="submenu-reestr" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.reestr') ? 'active' : '') }}">
                        <a href="{{ route('admin.reestr') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.reestr_load') ? 'active' : '') }}">
                        <a href="{{ route('admin.reestr_load') }}" class="nav-link">
                            <span class="sidebar-text">Загрузка</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <span class="nav-link {{ (Route::is('admin.reestr_org') || Route::is('admin.reestr_org_load') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-reestr-org">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-list-alt"></i>
                    </span>
                    <span class="sidebar-text">Реестр организаций</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.reestr_org') || Route::is('admin.reestr_org_load')  ? 'show' : '') }} " role="list" id="submenu-reestr-org" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.reestr_org') ? 'active' : '') }}">
                        <a href="{{ route('admin.reestr_org') }}" class="nav-link">
                            <span class="sidebar-text">Список</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Route::is('admin.reestr_org_load') ? 'active' : '') }}">
                        <a href="{{ route('admin.reestr_org_load') }}" class="nav-link">
                            <span class="sidebar-text">Загрузка</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <span class="nav-link {{ (Route::is('admin.intern_upload') ? '' : 'collapsed') }} d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-intern_upload">
                <span>
                    <span class="sidebar-icon">
                        <i class="fas fa-list-alt"></i>
                    </span>
                    <span class="sidebar-text">РосНОУ</span>
                </span>
                <span class="link-arrow">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse {{ (Route::is('admin.intern_upload')  ? 'show' : '') }} " role="list" id="submenu-intern_upload" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ (Route::is('admin.intern_upload') ? 'active' : '') }}">
                        <a href="{{ route('admin.intern_upload') }}" class="nav-link">
                            <span class="sidebar-text">Загрузка</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item  {{ (Route::is('admin.menu') ? 'active' : '') }} ">
            <a href="{{ route('admin.menu') }}" class="nav-link">
            <span class="sidebar-icon">
                <i class="fas fa-bars"></i>
            </span>
            <span class="sidebar-text">Меню</span>
            </a>
        </li>

    </ul>
  </div>
</nav>
