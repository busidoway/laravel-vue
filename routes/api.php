<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormFeedback;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReestrController;
use App\Http\Controllers\EventFormatController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\TypeProgramController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\FormEducationController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\ProgramsEduController;
use App\Http\Controllers\ProgramsGroupController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ReviewsCategoryController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventSubCategoryController;
use App\Http\Controllers\EventSubCategoryJoinController;
use App\Http\Controllers\EventPeriodController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Feedback

Route::post('feedback', [FormFeedback::class, 'index']);

// Check payment

Route::post('check_payment', [ReestrController::class, 'checkPayment']);

// Videos

Route::get('user_video/users', [VideoController::class, 'getUsers']);

Route::get('user_video/{video}', [VideoController::class, 'getUserVideo']);

Route::get('store_user_video/{video}', [VideoController::class, 'storeUserVideo']);

Route::post('video_list', [VideoController::class, 'getVideoList']);

Route::post('video_check_list', [VideoController::class, 'getCheckVideoList']);

// Events

Route::get('user_event_list/{user_id}', [UsersController::class, 'getUserEvents']);

Route::post('event_list', [UsersController::class, 'getEventList']);

Route::get('user_event_date/{id}/{user_id}', [UsersController::class, 'getUserEventDate']);

Route::post('event_store/{user_id}', [UsersController::class, 'storeUserEvents']);

Route::post('user_event_delete/{id}', [UsersController::class, 'deleteUserEvent']);

Route::post('user_event_delete_selected/{user_id}', [UsersController::class, 'deleteSelectedUserEvents']);

Route::get('event_date/{id}', [EventsController::class, 'getEventDate']);

Route::post('event_title/{id}', [EventsController::class, 'getEventTitle']);

// EventPeriod

Route::get('event_period_date/{id}/{event_id}', [EventPeriodController::class, 'getEventPeriodDate']);

// Menu

Route::get('menu/list', [MenuController::class, 'getMenuList']);

// Event video

Route::get('event_video_list/{event_id}', [EventsController::class, 'getEventVideo']);

Route::post('event_check_list', [EventsController::class, 'getCheckEventsList']);

Route::post('event_video_delete/{id}', [EventsController::class, 'deleteEventVideo']);

// Event person

Route::get('event_person/persons', [EventsController::class, 'getPerson']);

Route::get('event_person/{event}', [EventsController::class, 'getEventPerson']);

// Event format

Route::post('event_format', [EventFormatController::class, 'getEventFormat']);

Route::post('check_event_format', [EventFormatController::class, 'getCheckEventFormat']);

// Subscribe

Route::get('user_subscriptions/{id}', [UsersController::class, 'getUserSubscriptions']);

// Applications

Route::get('applications_group', [ApplicationsController::class, 'index']);

Route::get('applications_archive', [ApplicationsController::class, 'getAppArchive']);

Route::post('application_store', [ApplicationsController::class, 'store']);

Route::post('applications_list/{event_id}', [ApplicationsController::class, 'getAppList']);

Route::post('application_search/{data}', [ApplicationsController::class, 'searchApp']);

Route::post('application_delete/{id}', [ApplicationsController::class, 'destroy']);

Route::post('event_application_delete/{id}', [ApplicationsController::class, 'destroyEventApp']);

Route::post('applications_upload_data', [ApplicationsController::class, 'uploadAppData']);

// Reestr

Route::get('reestr_list', [ReestrController::class, 'index']);

Route::post('payment_reestr', [ReestrController::class, 'getPaymentReestr']);

Route::post('reestr_send_mail', [ReestrController::class, 'sendMail']);

Route::post('reestr_delete/{id}', [ReestrController::class, 'destroy']);

Route::post('reestr_contacts', [ReestrController::class, 'getReestrContacts']);

// Type programs

Route::get('type_programs', [TypeProgramController::class, 'index']);

Route::post('type_program_store', [TypeProgramController::class, 'store']);

Route::get('type_program_edit/{id}', [TypeProgramController::class, 'edit']);

Route::put('type_program_update/{id}', [TypeProgramController::class, 'update']);

Route::delete('type_program_delete/{id}', [TypeProgramController::class, 'destroy']);

Route::get('type_program_title/{id}', [TypeProgramController::class, 'getTypeProgramTitle']);

// Programs

Route::get('programs', [ProgramsController::class, 'index']);

Route::get('programs_filter/{cat}', [ProgramsController::class, 'getProgramsFilter']);

Route::post('programs_store', [ProgramsController::class, 'store']);

Route::get('programs_edit/{id}', [ProgramsController::class, 'edit']);

Route::put('programs_update/{id}', [ProgramsController::class, 'update']);

Route::delete('programs_delete/{id}', [ProgramsController::class, 'destroy']);

// Form education

Route::get('form_education', [FormEducationController::class, 'index']);

Route::get('form_education_filter/{cat}', [FormEducationController::class, 'getFormEduFilter']);

Route::post('form_education_store', [FormEducationController::class, 'store']);

Route::get('form_education_edit/{id}', [FormEducationController::class, 'edit']);

Route::put('form_education_update/{id}', [FormEducationController::class, 'update']);

Route::delete('form_education_delete/{id}', [FormEducationController::class, 'destroy']);

// Organizations

Route::get('organizations', [OrganizationsController::class, 'index']);

Route::get('organizations_filter/{cat}', [OrganizationsController::class, 'getOrgFilter']);

Route::post('organizations_store', [OrganizationsController::class, 'store']);

Route::get('organizations_edit/{id}', [OrganizationsController::class, 'edit']);

Route::post('organizations_update/{id}', [OrganizationsController::class, 'update']);

Route::delete('organizations_delete/{id}', [OrganizationsController::class, 'destroy']);

// Cities

Route::get('cities', [CitiesController::class, 'index']);

Route::get('cities_filter/{cat}', [CitiesController::class, 'getCitiesFilter']);

Route::post('cities_store', [CitiesController::class, 'store']);

Route::get('cities_edit/{id}', [CitiesController::class, 'edit']);

Route::put('cities_update/{id}', [CitiesController::class, 'update']);

Route::delete('cities_delete/{id}', [CitiesController::class, 'destroy']);

// Programs Education

Route::get('programs_edu', [ProgramsEduController::class, 'index']);

Route::get('programs_edu_all', [ProgramsEduController::class, 'all']);

Route::post('programs_edu_store', [ProgramsEduController::class, 'store']);

Route::get('programs_edu_edit/{id}', [ProgramsEduController::class, 'edit']);

Route::put('programs_edu_update/{id}', [ProgramsEduController::class, 'update']);

Route::delete('programs_edu_delete/{id}', [ProgramsEduController::class, 'destroy']);

// Programs group

Route::get('programs_group', [ProgramsGroupController::class, 'index']);

Route::post('programs_group_store', [ProgramsGroupController::class, 'store']);

Route::get('programs_group_edit/{id}', [ProgramsGroupController::class, 'edit']);

Route::put('programs_group_update/{id}', [ProgramsGroupController::class, 'update']);

Route::delete('programs_group_delete/{id}', [ProgramsGroupController::class, 'destroy']);

// Programs Applications

Route::get('programs_app_group/{id}', [ProgramsEduController::class, 'getProgramsEduApp']);

Route::get('programs_app_list/{id}', [ApplicationsController::class, 'getProgramAppList']);

// Image

Route::post('image_upload', [ImageUploadController::class, 'upload']);

// Exams

Route::get('exams', [ExamController::class, 'index']);

Route::post('exam_store', [ExamController::class, 'store']);

Route::get('exam_edit/{id}', [ExamController::class, 'edit']);

Route::put('exam_update/{id}', [ExamController::class, 'update']);

Route::delete('exam_delete/{id}', [ExamController::class, 'destroy']);

// Reviews

Route::get('reviews', [ReviewsController::class, 'index']);

Route::post('reviews_store', [ReviewsController::class, 'store']);

Route::get('reviews_edit/{id}', [ReviewsController::class, 'edit']);

Route::put('reviews_update/{id}', [ReviewsController::class, 'update']);

Route::delete('reviews_delete/{id}', [ReviewsController::class, 'destroy']);

Route::get('reviews_categories', [ReviewsCategoryController::class, 'index']);

Route::post('reviews_categories_store', [ReviewsCategoryController::class, 'store']);

Route::get('reviews_categories_edit/{id}', [ReviewsCategoryController::class, 'edit']);

Route::put('reviews_categories_update/{id}', [ReviewsCategoryController::class, 'update']);

Route::delete('reviews_categories_delete/{id}', [ReviewsCategoryController::class, 'destroy']);

// Interns

Route::post('intern_upload_data', [InternController::class, 'uploadInternData']);

// EventCategories

Route::get('event_categories', [EventCategoryController::class, 'index']);

Route::post('event_categories_store', [EventCategoryController::class, 'store']);

Route::get('event_categories_edit/{id}', [EventCategoryController::class, 'edit']);

Route::put('event_categories_update/{id}', [EventCategoryController::class, 'update']);

Route::delete('event_categories_delete/{id}', [EventCategoryController::class, 'destroy']);

// EventSubCategories

Route::get('event_sub_categories', [EventSubCategoryController::class, 'index']);

Route::post('event_sub_categories_store', [EventSubCategoryController::class, 'store']);

Route::get('event_sub_categories_edit/{id}', [EventSubCategoryController::class, 'edit']);

Route::put('event_sub_categories_update/{id}', [EventSubCategoryController::class, 'update']);

Route::delete('event_sub_categories_delete/{id}', [EventSubCategoryController::class, 'destroy']);

Route::post('data_event_sub_categories', [EventSubCategoryController::class, 'getEventSubCategories']);

Route::get('data_event_sub_categories/{id}', [EventSubCategoryController::class, 'getDataEventSubCategories']);

// EventSubCategoryJoin

Route::post('event_sub_category_join_store', [EventSubCategoryJoinController::class, 'store']);

Route::get('event_sub_category_join_edit/{id}', [EventSubCategoryJoinController::class, 'edit']);

Route::put('event_sub_category_join_update/{id}', [EventSubCategoryJoinController::class, 'update']);
