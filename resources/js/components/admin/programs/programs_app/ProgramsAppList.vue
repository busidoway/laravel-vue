<template>
    <div class="programs-app-list">
        <div class="programs-app-page">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-end pt-2 mb-3">
                <nav-page :nav-list="dataNav" />
            </div>
            <div class="d-flex justify-content-between align-items-end mb-4">
                <router-link :to="'/admin/' + sectionApp + '/' + sectionAppId + '/page/' + pageAppBack" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i>Назад</router-link>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <upload-app-data :app-data="appData" :event-id="programId" />
                </div>
            </div>
            <div class="card card-body border-0 shadow table-wrapper table-responsive">
                <div class="text-danger mb-3" v-if="status.error">{{ status.info }}</div>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-gray-400" role="status" v-if="loading">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <table class="table table-app table-reestr table-hover">
                    <thead>
                        <tr>
                            <th class="border-gray-200">ID</th>
                            <th class="border-gray-200">Имя отправителя</th>
                            <th class="border-gray-200">Email</th>
                            <th class="border-gray-200">Телефон</th>
                            <th class="border-gray-200">Дата отправки</th>
                            <th class="border-gray-200">Член/запись</th>
                            <th class="border-gray-200">Публ. в реестре</th>
                            <th class="border-gray-200">Оплата членства</th>
                            <th class="border-gray-200">Срок аттестата</th>
                            <th class="border-gray-200"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in dataList" :key="item.id">
                        <td class="table-app__id" data-label="ID">
                            <a href="#" class="fw-bold">
                                {{ item.id }}
                            </a>
                        </td>
                        <td data-label="Имя отправителя">
                            <span class="fw-normal">{{ item.last_name_sender }} {{ item.name_sender }} {{ item.middle_name_sender }}</span>
                        </td>
                        <td data-label="Email">
                            <span class="fw-normal">{{ item.email_sender }}</span>
                        </td>
                        <td data-label="Телефон">
                            <span class="fw-normal">{{ item.phone_sender }}</span>
                        </td>
                        <td data-label="Дата отправки">
                            <div class="d-flex align-items-center" v-if="item.status === 0" title="Ошибка отправки">
                                <i class="fas fa-exclamation-circle text-danger me-1"></i>
                                <span class="text-danger fw-normal">{{ item.date_send }}</span>
                            </div>
                            <div class="d-flex align-items-center" v-else>
                                <span class="fw-normal">{{ item.date_send }}</span>
                            </div>
                        </td>
                        <td data-label="Член/запись">
                            <div class="" v-if="item.reestr.length !== 0">
                                <span class="text-success" v-if="item.reestr.membership == 1">Членство</span>
                                <span class="text-info" v-else>Запись</span>
                            </div>
                            <div v-else>
                                <span class="text-danger">Чужой</span>
                            </div>
                        </td>
                        <td data-label="Публ. в реестре">
                            <div v-if="item.payment_reestr.length !== 0">
                                <div v-for="pr in item.payment_reestr">
                                    <div v-if="pr.name == 'reestr'" class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-1" v-if="parseInt(pr.status) == 1"></i>
                                        <i class="fas fa-exclamation-circle text-danger me-1" v-else-if="parseInt(pr.status) == 0"></i>
                                        <span :class="[parseInt(pr.status) == 1 ? 'text-success' : '', parseInt(pr.status) == 0 ? 'text-danger' : '']">{{ pr.year }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Оплата членства">
                            <div v-if="item.payment_reestr.length !== 0">
                                <div v-for="pr in item.payment_reestr">
                                    <div v-if="pr.name === 'membership'" class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-1" v-if="parseInt(pr.status) === 1"></i>
                                        <i class="fas fa-exclamation-circle text-danger me-1" v-else-if="parseInt(pr.status) === 0"></i>
                                        <span :class="[parseInt(pr.status) === 1 ? 'text-success' : '', parseInt(pr.status) === 0 ? 'text-danger' : '']">{{ pr.year }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Срок аттестата">
                            <span v-if="item.reestr.length !== 0" :class="[item.reestr.expired ? 'text-danger' : '']">{{ item.reestr.date_end }}</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="icon icon-sm">
                                    <span class="fas fa-ellipsis-h icon-dark"></span>
                                </span>
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu py-0">
                                    <button type="button" class="dropdown-item text-danger rounded-bottom" data-bs-toggle="modal" data-bs-target="#modal-delete" @click.prevent="getAppData(item.id)">
                                        <span class="fas fa-trash-alt me-2"></span>Удалить
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between" v-if="!loading">
                    <PaginationNav :total="dataTotal" :item="dataCount" :active-page="pageCurr" @page-changed="loadPageData" />
                </div>
                <modal-delete :id="dataItem.id" :title="dataItem.title" @delete-confirm="deleteDataItem" />
            </div>
        </div>
    </div>
</template>

<script>
import PaginationNav from "../../components/PaginationNav.vue";
import ModalDelete from "../../components/ModalDelete.vue";
import axios from "axios";
import AppSearch from "../../components/Search.vue";
import NavPage from "../../components/NavPage.vue";
import UploadAppData from "../../applications/components/UploadAppData.vue";

export default {
    components: {
        UploadAppData,
        NavPage,
        AppSearch,
        ModalDelete,
        PaginationNav
    },
    props: {

    },
    data: () => ({
        dataArr: [],
        dataItem: {
            id: null,
            title: ""
        },
        dataNav: [
            {
                title: 'Заявки',
                active: true,
                path: '/admin/programs_app/',
            }
        ],
        navTitle: 'Заявки',
        titleSection: "",
        appData: [],
        programId: null,
        appItemName: 'ProgramsAppGroup',
        dataTotal: 0,
        dataCount: 10,
        pagePath: '/admin/programs_app/',
        pageCurr: 0,
        pageCurrApp: null,
        sectionApp: 'programs_app',
        sectionAppId: null,
        pageAppBack: 1,
        isArchive: false,
        loading: true,
        status: {
            success: false,
            error: false,
            info: ""
        },
    }),
    computed: {
        dataList() {
            let resultData = [];

            resultData = this.dataArr;

            // постраничная навигация и разбивка на страницы
            let currPage = parseInt(this.$route.params.page);
            let pageNumber = 1;

            if (currPage) {
                pageNumber = currPage;
            }

            // if (this.setFilter) {
            //     pageNumber = 1;
            //     this.$emit('unset-filter', true);
            // }

            let startPage = (this.dataCount * pageNumber) - this.dataCount;
            let endPage = pageNumber * this.dataCount;
            let arrApp = [];
            let n = 0;

            this.dataTotal = resultData.length;

            for (let i = 0; i < resultData.length; i++) {
                if (i >= startPage && i < endPage) {
                    n++;
                    arrApp.push(resultData[i]);
                }
            }

            this.loadPageData(pageNumber);

            return arrApp;
        }
    },
    created() {
        this.getDataList()
    },
    methods: {
        getDataList() {

            let currPage = this.$route.params.page;
            let catId = null;
            let programEduId = null;
            this.pageCurrApp = currPage;
            if(this.$route.params.id) catId = this.$route.params.id;
            if(this.$route.params.id_program_edu) programEduId = this.$route.params.id_program_edu;
            let api_path = '/api/programs_app_list/' + programEduId;

            this.sectionAppId = catId;

            if(this.appArchive){
                api_path = '/api/applications_archive';
            }

            axios.get(api_path)
                .then(response => {
                    this.status.error = false;
                    this.status.success = true;
                    console.log(response.data.applications)
                    this.dataArr = response.data.applications;
                })
                .catch(error => {
                    this.status.success = false;
                    this.status.error = true;
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.response.info = error.response.data.errors.name[0];
                    } else {
                        this.response.info = 'Произошла ошибка при загрузке данных.';
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        loadPageData(pageNumber) {
            let path = this.pagePath + this.$route.params.id + '/' + this.$route.params.id_program_edu + '/page/' + pageNumber;
            this.pageCurr = pageNumber;

            if (this.$route.path !== path) {
                this.$router.push(this.pagePath + this.$route.params.id + '/' + this.$route.params.id_program_edu + '/page/' + pageNumber);
            }
        },
        getDataItem(id) {
            this.dataItem.id = id;
            this.dataItem.title = 'запись № ' + id;
        },
        deleteDataItem(id) {
            if (id) {
                axios.delete(`/api/programs_edu_delete/${id}`)
                    .then(response => {
                        if (response.data.status === true) {
                            this.status.error = false;
                            this.status.success = true;
                            this.getDataList();
                        } else {
                            this.status.success = false;
                            this.status.error = true;
                            this.response.info = 'Произошла ошибка при удалении данных.';
                        }
                    })
                    .catch(error => {
                        this.status.success = false;
                        this.status.error = true;
                        if (error.response && error.response.data && error.response.data.errors) {
                            this.response.info = error.response.data.errors.name[0];
                        } else {
                            this.response.info = 'Произошла ошибка при удалении данных.';
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }

}
</script>
