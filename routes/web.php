<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\PersonsController;
use App\Http\Controllers\ReestrController;
use App\Http\Controllers\ReestrOrgController;
use App\Http\Controllers\ViewpointsController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\EventFormatController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReviewsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register'=>true]);

Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Route::get('/', [NewsCategoryController::class, 'all'])->name('news_category_home');

Route::get('/news', function () {
    return view('pages.news');
})->name('news');

Route::get('/news/{id}', [NewsController::class, 'show'])->name('news_inner');

Route::get('/events', function () {
    return view('pages.events');
})->name('events');

Route::get('/events_archive', function () {
    return view('pages.events_archive');
})->name('events_archive');

Route::get('/events/{id}', [EventsController::class, 'show'])->name('event_inner');

Route::get('/membership', function () {
    return view('pages.membership');
})->name('membership');

Route::get('/general_opinion', function () {
    return view('pages.other.general_opinion');
})->name('general_opinion');

Route::get('/case_club', function () {
    return view('pages.other.case_club');
})->name('case_club');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/activities', function () {
    return view('pages.other.activities');
})->name('activities');

Route::get('/contacts', function () {
    return view('pages.contacts');
})->name('contacts');

Route::get('/check_payment', function () {
    return view('pages.check_payment');
})->name('check_payment');

Route::post('/check_payment', [ReestrController::class, 'checkPayment'])->name('reestr_check_payment');

Route::get('/date_exam', function () {
    return view('pages.other.date_exam.date_exam');
})->name('date_exam');

Route::get('/programs', function () {
    return view('pages.main.programs.programs');
})->name('programs');
Route::get('/programs/{any}', function () {
    return view('pages.main.programs.programs');
})->where('any', '.*');

Route::get('/license', function () {
    return view('pages.info.license');
})->name('license');

Route::get('/reviews', [ReviewsController::class, 'getAll'])->name('reviews');

Route::redirect('/reestr.php', '/reestr');

Route::get('/events_video', [EventsController::class, 'getEventsVideo'])->name('events_video');

Route::get('/events_video/{id}', [EventsController::class, 'showEventVideo'])->name('event_video_inner');

Route::get('/events_video/view/{id}', [EventsController::class, 'viewEventVideo'])->name('event_video_view');

Route::get('/auth', [App\Http\Controllers\HomeController::class, 'index'])->name('auth');

Route::group(['middleware' => ['auth', 'isadmin']], function()
{
    Route::get('/admin', function () {
        return redirect('/admin/applications');
    })->name('admin');

    Route::resource('/admin/video', VideoController::class);

    Route::resource('/admin/users', UsersController::class);

    Route::resource('/admin/events', EventsController::class);

    // Route::resource('/admin/event_category', EventCategoryController::class);

    Route::resource('/admin/event_format', EventFormatController::class);

    Route::resource('/admin/news', NewsController::class);

    Route::resource('/admin/slider', SliderController::class);

    Route::resource('/admin/news_category', NewsCategoryController::class);

    Route::resource('/admin/persons', PersonsController::class);

    Route::resource('/reestr', ReestrController::class);

    Route::resource('/reestr_org', ReestrOrgController::class);

    Route::resource('/admin/viewpoints', ViewpointsController::class);

    Route::resource('/admin/menu', MenuController::class);

    Route::resource('/admin/video_category', VideoCategoryController::class);

    // Route::resource('/admin/applications', ApplicationsController::class);
});

Route::get('/reestr', [ReestrController::class, 'all'])->name('reestr');

Route::get('/person.php', [ReestrController::class, 'redir']);

Route::get('/person/{id}', [ReestrController::class, 'person'])->name('person');

Route::get('/reestr_org', [OrganizationsController::class, 'all'])->name('reestr_org');

Route::get('/org/{id}', [OrganizationsController::class, 'show'])->name('org');

Route::get('/viewpoint', [ViewpointsController::class, 'all'])->name('viewpoint');

Route::get('/viewpoint/{id}', [ViewpointsController::class, 'show'])->name('viewpoint_inner');

Route::group(['middleware' => ['auth', 'isadmin'], 'namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function()
{

    Route::get('/slider', [SliderController::class, 'index'])->name('slider');
    Route::post('/slider_sort_up/{id}', [SliderController::class, 'sortUp'])->name('slider_sort_up');
    Route::post('/slider_sort_down/{id}', [SliderController::class, 'sortDown'])->name('slider_sort_down');
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/users/{id}/set', [UsersController::class, 'set'])->name('users_set');
    Route::get('/video', [VideoController::class, 'index'])->name('video');
    Route::get('/video_load', [VideoController::class, 'indexUpload'])->name('video_load');
    Route::post('/video_upload', [VideoController::class, 'uploadVideo'])->name('video_upload');
    Route::get('/video_category', [VideoCategoryController::class, 'index'])->name('video_category');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/news_category', [NewsCategoryController::class, 'index'])->name('news_category');
    Route::get('/events', [EventsController::class, 'index'])->name('events');
    // Route::get('/event_category', [EventCategoryController::class, 'index'])->name('event_category');
    Route::get('/event_format', [EventFormatController::class, 'index'])->name('event_format');
    Route::get('/persons', [PersonsController::class, 'index'])->name('persons');
    // Route::get('/reestr', [ReestrController::class, 'index'])->name('reestr');
    Route::get('/reestr_load', [ReestrController::class, 'indexUpload'])->name('reestr_load');
    Route::post('/reestr_upload', [ReestrController::class, 'uploadReestr'])->name('reestr_upload');
    Route::get('/reestr_org', [ReestrOrgController::class, 'index'])->name('reestr_org');
    Route::get('/reestr_org_load', [ReestrOrgController::class, 'indexUpload'])->name('reestr_org_load');
    Route::post('/reestr_org_upload', [ReestrOrgController::class, 'uploadReestr'])->name('reestr_org_upload');
    Route::get('/viewpoints', [ViewpointsController::class, 'index'])->name('viewpoints');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    // Заявки
    Route::get('/applications', function () {
        return view('admin.pages.applications.applications');
    })->name('applications');
    Route::get('/applications/{any}', function () {
        return view('admin.pages.applications.applications');
    })->where('any', '.*');
    Route::get('/app_archive', function () {
        return view('admin.pages.applications.applications');
    })->name('app_archive');
    Route::get('/app_archive/{any}', function () {
        return view('admin.pages.applications.applications');
    })->where('any', '.*');

    // Категории мероприятий
    Route::get('/event_categories', function () {
        return view('admin.pages.events.event_categories.event_categories');
    })->name('event_categories');
    Route::get('/event_categories/{any}', function () {
        return view('admin.pages.events.event_categories.event_categories');
    })->where('any', '.*');

    // Подкатегории мероприятий
    Route::get('/event_sub_categories', function () {
        return view('admin.pages.events.event_sub_categories.event_sub_categories');
    })->name('event_sub_categories');
    Route::get('/event_sub_categories/{any}', function () {
        return view('admin.pages.events.event_sub_categories.event_sub_categories');
    })->where('any', '.*');

    // Реестр
    Route::get('/reestr', function () {
        return view('admin.pages.reestr');
    })->name('reestr');
    Route::get('/reestr/{any}', function () {
        return view('admin.pages.reestr');
    })->where('any', '.*');

    // Наборы программ
    Route::get('/programs_edu', function () {
        return view('admin.pages.programs.programs_edu');
    })->name('programs_edu');
    Route::get('/programs_edu/{any}', function () {
        return view('admin.pages.programs.programs_edu');
    })->where('any', '.*');

    // Типы программ
    Route::get('/type_programs', function () {
        return view('admin.pages.programs.type_programs');
    })->name('type_programs');
    Route::get('/type_programs/{any}', function () {
        return view('admin.pages.programs.type_programs');
    })->where('any', '.*');

    // Группы программ
    Route::get('/programs_groups', function () {
        return view('admin.pages.programs.programs_groups');
    })->name('programs_groups');
    Route::get('/programs_groups/{any}', function () {
        return view('admin.pages.programs.programs_groups');
    })->where('any', '.*');

    // Программы
    Route::get('/programs', function () {
        return view('admin.pages.programs.programs');
    })->name('programs');
    Route::get('/programs/{any}', function () {
        return view('admin.pages.programs.programs');
    })->where('any', '.*');

    // Формы обучения
    Route::get('/form_education', function () {
        return view('admin.pages.programs.form_education');
    })->name('form_education');
    Route::get('/form_education/{any}', function () {
        return view('admin.pages.programs.form_education');
    })->where('any', '.*');

    // Организации
    Route::get('/organizations', function () {
        return view('admin.pages.organizations.organizations');
    })->name('organizations');
    Route::get('/organizations/{any}', function () {
        return view('admin.pages.organizations.organizations');
    })->where('any', '.*');

    // Города
    Route::get('/cities', function () {
        return view('admin.pages.cities.cities');
    })->name('cities');
    Route::get('/cities/{any}', function () {
        return view('admin.pages.cities.cities');
    })->where('any', '.*');

    // Экзамены
    Route::get('/exams', function () {
        return view('admin.pages.exams.exams');
    })->name('exams');
    Route::get('/exams/{any}', function () {
        return view('admin.pages.exams.exams');
    })->where('any', '.*');

    // Заявки программ
    Route::get('/programs_app', function () {
        return view('admin.pages.programs.programs_app');
    })->name('programs_app');
    Route::get('/programs_app/{any}', function () {
        return view('admin.pages.programs.programs_app');
    })->where('any', '.*');

    // Отзывы
    Route::get('/reviews', function () {
        return view('admin.pages.reviews.reviews');
    })->name('reviews');
    Route::get('/reviews/{any}', function () {
        return view('admin.pages.reviews.reviews');
    })->where('any', '.*');
    Route::get('/reviews_categories', function () {
        return view('admin.pages.reviews.reviews_categories');
    })->name('reviews_categories');
    Route::get('/reviews_categories/{any}', function () {
        return view('admin.pages.reviews.reviews_categories');
    })->where('any', '.*');

    // Interns
    Route::get('/intern_upload', function () {
        return view('admin.pages.interns.intern_upload');
    })->name('intern_upload');

    // Test
    Route::get('/test/composition_api', function () {
        return view('admin.test.composition_api');
    })->name('composition_api');
    Route::get('/test/composition_api/{any}', function () {
        return view('admin.test.composition_api');
    })->where('any', '.*');

});
