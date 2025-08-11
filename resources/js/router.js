import vueRouter from "vue-router";
import Vue from "vue";

Vue.use(vueRouter);

import Applications from "./components/admin/applications/Applications.vue";
import ApplicationsGroupItem from "./components/admin/applications/ApplicationsGroupItem.vue";
import Reestr from "./components/admin/reestr/Reestr.vue";
import ReestrMail from "./components/admin/reestr/ReestrMail.vue";
import ApplicationsArchive from "./components/admin/applications/ApplicationsArchive.vue";
import ProgramList from "./components/admin/ProgramList.vue";
import TypePrograms from "./components/admin/programs/type_programs/TypePrograms.vue";
import TypeProgramsEdit from "./components/admin/programs/type_programs/TypeProgramsEdit.vue";
import Programs from "./components/admin/programs/programs/Programs.vue";
import ProgramsEdit from "./components/admin/programs/programs/ProgramsEdit.vue";
import FormEducation from "./components/admin/programs/form_education/FormEducation.vue";
import FormEducationEdit from "./components/admin/programs/form_education/FormEducationEdit.vue";
import Organizations from "./components/admin/organizations/Organizations.vue";
import OrganizationsEdit from "./components/admin/organizations/OrganizationsEdit.vue";
import Cities from "./components/admin/cities/Cities.vue";
import CitiesEdit from "./components/admin/cities/CitiesEdit.vue";
import ProgramsEdu from "./components/admin/programs/programs_edu/ProgramsEdu.vue";
import ProgramsEduEdit from "./components/admin/programs/programs_edu/ProgramsEduEdit.vue";
import ProgramsGroup from "./components/admin/programs/programs_group/ProgramsGroup.vue";
import ProgramsGroupEdit from "./components/admin/programs/programs_group/ProgramsGroupEdit.vue";
import Exams from "./components/admin/exams/Exams.vue";
import ExamsEdit from "./components/admin/exams/ExamsEdit.vue";
import ProgramsApp from "./components/admin/programs/programs_app/ProgramsApp.vue";
import ProgramsAppGroup from "./components/admin/programs/programs_app/ProgramsAppGroup.vue";
import ProgramsAppList from "./components/admin/programs/programs_app/ProgramsAppList.vue";
import Reviews from "./components/admin/reviews/reviews/Reviews.vue";
import ReviewsEdit from "./components/admin/reviews/reviews/ReviewsEdit.vue";
import ReviewsCategories from "./components/admin/reviews/reviews_categories/ReviewsCategories.vue";
import ReviewsCategoriesEdit from "./components/admin/reviews/reviews_categories/ReviewsCategoriesEdit.vue";
import InternUpload from "./components/admin/interns/intern_upload/InernUpload.vue";
import EventCategories from "./components/admin/events/event_categories/EventCategories.vue";
import EventCategoriesCreate from "./components/admin/events/event_categories/EventCategoriesCreate.vue";
import EventCategoriesEdit from "./components/admin/events/event_categories/EventCategoriesEdit.vue";
import EventSubCategories from "./components/admin/events/event_sub_categories/EventSubCategories.vue";
import EventSubCategoriesEdit from "./components/admin/events/event_sub_categories/EventSubCategoriesEdit.vue";

import ProgramsMain from "./components/main/programs/ProgramsMain.vue";

const routes = [

    // Заявки

    {
        path: "/admin/applications",
        component: Applications,
        name: 'Apps'
    },
    {
        path: "/admin/applications/page/:page",
        component: Applications
    },
    {
        path: "/admin/applications/:id",
        name: 'appItem',
        component: ApplicationsGroupItem,
        props: true
    },
    {
        path: "/admin/applications/:id/page/:page",
        component: ApplicationsGroupItem
    },

    // Архив заявок

    {
        path: "/admin/app_archive",
        component: ApplicationsArchive,
        name: 'AppArchive'
    },
    {
        path: "/admin/app_archive/page/:page",
        component: ApplicationsArchive
    },
    {
        path: "/admin/app_archive/:id",
        name: 'appArchiveItem',
        component: ApplicationsGroupItem,
        props: true
    },
    {
        path: "/admin/app_archive/:id/page/:page",
        component: ApplicationsGroupItem
    },

    // Реестр

    {
        path: "/admin/reestr",
        name: 'Reestr',
        component: Reestr
    },
    {
        path: "/admin/reestr/page/:page",
        component: Reestr
    },
    {
        path: "/admin/reestr/mail",
        name: 'ReestrMail',
        component: ReestrMail,
        props: true
    },

    // Список программ

    {
        path: "/admin/program_list",
        component: ProgramList,
        name: 'ProgramsList'
    },
    {
        path: "/admin/program_list/page/:page",
        component: ProgramList
    },

    // Типы программ

    {
        path: "/admin/type_programs",
        component: TypePrograms,
        name: 'TypePrograms'
    },
    {
        path: "/admin/type_programs/page/:page",
        component: TypePrograms
    },
    {
        path: "/admin/type_programs/edit",
        component: TypeProgramsEdit,
        name: 'TypeProgramsCreate'
    },
    {
        path: "/admin/type_programs/edit/:id",
        component: TypeProgramsEdit,
        name: 'TypeProgramsEdit',
        props: true
    },

    // Программы

    {
        path: "/admin/programs",
        component: Programs,
        name: 'Programs'
    },
    {
        path: "/admin/programs/page/:page",
        component: Programs
    },
    {
        path: "/admin/programs/edit",
        component: ProgramsEdit,
        name: 'ProgramsCreate'
    },
    {
        path: "/admin/programs/edit/:id",
        component: ProgramsEdit,
        name: 'ProgramsEdit',
        props: true
    },

    // Формы обучения

    {
        path: "/admin/form_education",
        component: FormEducation,
        name: 'FormEducation'
    },
    {
        path: "/admin/form_education/page/:page",
        component: FormEducation
    },
    {
        path: "/admin/form_education/edit",
        component: FormEducationEdit,
        name: 'FormEducationCreate'
    },
    {
        path: "/admin/form_education/edit/:id",
        component: FormEducationEdit,
        name: 'FormEducationEdit',
        props: true
    },

    // Организации

    {
        path: "/admin/organizations",
        component: Organizations,
        name: 'Organizations'
    },
    {
        path: "/admin/organizations/page/:page",
        component: Organizations
    },
    {
        path: "/admin/organizations/edit",
        component: OrganizationsEdit,
        name: 'OrganizationsCreate'
    },
    {
        path: "/admin/organizations/edit/:id",
        component: OrganizationsEdit,
        name: 'OrganizationsEdit',
        props: true
    },

    // Города

    {
        path: "/admin/cities",
        component: Cities,
        name: 'Cities'
    },
    {
        path: "/admin/cities/page/:page",
        component: Cities
    },
    {
        path: "/admin/cities/edit",
        component: CitiesEdit,
        name: 'CitiesCreate'
    },
    {
        path: "/admin/cities/edit/:id",
        component: CitiesEdit,
        name: 'CitiesEdit',
        props: true
    },

    // Наборы программ

    {
        path: "/admin/programs_edu",
        component: ProgramsEdu,
        name: 'ProgramsEdu'
    },
    {
        path: "/admin/programs_edu/page/:page",
        component: ProgramsEdu
    },
    {
        path: "/admin/programs_edu/edit",
        component: ProgramsEduEdit,
        name: 'ProgramsEduCreate'
    },
    {
        path: "/admin/programs_edu/edit/:id",
        component: ProgramsEduEdit,
        name: 'ProgramsEduEdit',
        props: true
    },

    // Группы программ

    {
        path: "/admin/programs_groups",
        component: ProgramsGroup,
        name: 'ProgramsGroup'
    },
    {
        path: "/admin/programs_groups/page/:page",
        component: ProgramsGroup
    },
    {
        path: "/admin/programs_groups/edit",
        component: ProgramsGroupEdit,
        name: 'ProgramsGroupCreate'
    },
    {
        path: "/admin/programs_groups/edit/:id",
        component: ProgramsGroupEdit,
        name: 'ProgramsGroupEdit',
        props: true
    },

    // Заявки программ

    {
        path: "/admin/programs_app",
        component: ProgramsApp,
        name: 'ProgramsApp',
        props: true
    },
    {
        path: "/admin/programs_app/page/:page",
        component: ProgramsApp
    },
    {
        path: "/admin/programs_app/:id",
        component: ProgramsAppGroup,
        name: 'ProgramsAppGroup',
        props: true
    },
    {
        path: "/admin/programs_app/:id/page/:page",
        component: ProgramsAppGroup
    },
    {
        path: "/admin/programs_app/:id/:id_program_edu",
        component: ProgramsAppList,
        name: 'ProgramsAppList',
        props: true
    },
    {
        path: "/admin/programs_app/:id/:id_program_edu/page/:page",
        component: ProgramsAppList
    },

    // Экзамены

    {
        path: "/admin/exams",
        component: Exams,
        name: 'Exams'
    },
    {
        path: "/admin/exams/page/:page",
        component: Exams
    },
    {
        path: "/admin/exams/edit",
        component: ExamsEdit,
        name: 'ExamsCreate'
    },
    {
        path: "/admin/exams/edit/:id",
        component: ExamsEdit,
        name: 'ExamsEdit',
        props: true
    },

    // Отзывы

    {
        path: "/admin/reviews",
        component: Reviews,
        name: 'Reviews'
    },
    {
        path: "/admin/reviews/page/:page",
        component: Reviews
    },
    {
        path: "/admin/reviews/edit",
        component: ReviewsEdit,
        name: 'ReviewsCreate'
    },
    {
        path: "/admin/reviews/edit/:id",
        component: ReviewsEdit,
        name: 'ReviewsEdit',
        props: true
    },
    {
        path: "/admin/reviews_categories",
        component: ReviewsCategories,
        name: 'ReviewsCategories'
    },
    {
        path: "/admin/reviews_categories/page/:page",
        component: ReviewsCategories
    },
    {
        path: "/admin/reviews_categories/edit",
        component: ReviewsCategoriesEdit,
        name: 'ReviewsCategoriesCreate'
    },
    {
        path: "/admin/reviews_categories/edit/:id",
        component: ReviewsCategoriesEdit,
        name: 'ReviewsCategoriesEdit',
        props: true
    },

    // Программы на сайте

    {
        path: "/programs",
        component: ProgramsMain,
        name: 'ProgramsMain'
    },
    {
        path: "/programs/:code/:page",
        component: ProgramsMain
    },

    // Interns

    {
        path: "/admin/intern_upload",
        component: InternUpload,
        name: 'InternUpload'
    },

    // EventCategories

    {
        path: "/admin/event_categories",
        component: EventCategories,
        name: 'EventCategories'
    },
    {
        path: "/admin/event_categories/page/:page",
        component: EventCategories
    },
    {
        path: "/admin/event_categories/create",
        component: EventCategoriesCreate,
        name: 'EventCategoriesCreate'
    },
    {
        path: "/admin/event_categories/edit/:id",
        component: EventCategoriesEdit,
        name: 'EventCategoriesEdit',
        props: true
    },

    // EventSubCategories

    {
        path: "/admin/event_sub_categories",
        component: EventSubCategories,
        name: 'EventSubCategories'
    },
    {
        path: "/admin/event_sub_categories/page/:page",
        component: EventSubCategories
    },
    {
        path: "/admin/event_sub_categories/edit",
        component: EventSubCategoriesEdit,
        name: 'EventSubCategoriesCreate'
    },
    {
        path: "/admin/event_sub_categories/edit/:id",
        component: EventSubCategoriesEdit,
        name: 'EventSubCategoriesEdit',
        props: true
    },
];

export default new vueRouter({
    mode: "history",
    routes
})
