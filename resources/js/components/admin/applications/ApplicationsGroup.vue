<template>
    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <div class="text-danger mb-3" v-if="status.error">{{ status.info }}</div>
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-gray-400" role="status" v-if="loading">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table class="table table-app table-hover">
            <thead>
            <tr>
                <th class="border-gray-200">#</th>
                <th class="border-gray-200">Заголовок мероприятия</th>
                <th class="border-gray-200">Дата</th>
                <th class="border-gray-200">Кол-во заявок</th>
                <th class="border-gray-200"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in appList">
                <td class="table-app__id">
                    <a href="#" class="fw-bold">
                        {{ index + 1 }}
                    </a>
                </td>
                <td>
                    <RouterLink :to="{ name: appItemName, params: { id: item.id, pageApp: pageCurrApp, archive: isArchive } }" class="text-info py-2">
                        <i class="fas fa-envelope me-2"></i>
                        <span class="fw-normal" :title="item.title.replace(/(<([^>]+)>)/gi, '')">{{ item.title_short.replace(/(<([^>]+)>)/gi, '') }}</span>
                        <span v-if="item.title_length > item.title_count">...</span>
                    </RouterLink>
                </td>
                <td>
                    <span>{{ item.date_public }}</span>
                </td>
                <td class="text-center">
                    <span>{{ item.count_app }}</span>
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
                            <button type="button" class="dropdown-item text-danger rounded-bottom" data-bs-toggle="modal" data-bs-target="#modal-delete" @click.prevent="getAppData(item.id, index + 1)">
                                <span class="fas fa-trash-alt me-2"></span>Удалить
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between" v-if="!loading">
            <pagination-nav :total="appTotal" :item="appCount" @page-changed="loadPageApp" />
        </div>
        <modal-delete :id="appItem.id" :title="appItem.title + appItem.index + ' и все внутренние заявки'" @delete-confirm="deleteAppItem" />
    </div>
</template>
<script>
import PaginationNav from "../components/PaginationNav.vue";
import ModalDelete from "../components/ModalDelete.vue";
import axios from "axios";

export default {
    components: {
        PaginationNav,
        ModalDelete
    },
    props: {
        appArchive: {
            type: Boolean,
            default: false
        },
        searchReq: String
    },
    emits: [
        'app-data-list'
    ],
    data: () => ({
        // appList: [],
        appData: [],
        appTotal: 0,
        appCount: 10,
        appCurrCount: 0,
        page: 1,
        appItem: {
            id: null,
            index: null,
            title: "запись №"
        },
        loading: true,
        status: {
            success: false,
            error: false,
            info: ""
        },
        pageCurrApp: null,
        pagePath: '/admin/applications/page/',
        appItemName: 'appItem',
        isArchive: false
    }),
    computed: {
        appList() {
            let resultData = [];
            // поиск
            let search = this.searchReq.toLowerCase().trim().split(/\s/).filter( (el) => {
                return el != '';
            });
            if(search != "") {
                let searchStr = search.join(" ");
                resultData = this.appData.filter( (elem) => {
                    return elem.title.replace(/(<([^>]+)>)/gi, '').toLowerCase().indexOf(searchStr) !== -1
                })
            }else{
                resultData = this.appData
            }

            if(this.appArchive){
                this.pagePath = '/admin/app_archive/page/';
                this.appItemName = 'appArchiveItem';
                this.isArchive = true
            }

            // постраничная навигация и разбивка на страницы
            let currPage = this.$route.params.page;
            let pageNumber = 1;
            if (currPage) {
                pageNumber = currPage
            }
            let startPage = (this.appCount * pageNumber) - this.appCount;
            let endPage = pageNumber * this.appCount;
            let arrApp = [];
            let n = 0;

            this.appTotal = resultData.length;

            for(let i = 0; i < resultData.length; i++){
                if(i >= startPage && i < endPage){
                    n++;
                    arrApp.push(resultData[i]);
                }
            }

            this.loadPageApp(pageNumber);

            return arrApp;
        }
    },
    mounted() {
        this.getAppList();
    },
    methods: {
        getAppList() {
            let currPage = this.$route.params.page;
            this.pageCurrApp = currPage;
            let api_path = '/api/applications_group';

            if(this.appArchive){
                api_path = '/api/applications_archive';
                // this.pagePath = '/admin/app_archive/page/';
                // this.appItemName = 'appArchiveItem';
                // this.isArchive = true
            }

            axios.get(api_path)
                .then(resp => {
                    if(resp.data) {
                        let respData = resp.data.applications;
                        this.status.error = false;
                        this.loading = false;
                        this.appData = respData;
                        this.appCurrCount = respData.length;
                        this.appTotal = respData.length;
                        this.$emit('app-data-list', respData);

                        if (currPage) {
                            this.loadPageApp(currPage)
                        } else if (this.appTotal > this.appCount) {
                            this.loadPageApp(1)
                        }
                    }
                }).catch(err => {
                this.status.error = true;
                this.status.info = err;
            })
        },
        loadPageApp(pageNumber) {
            let path = this.pagePath + pageNumber;
            if (this.$route.path !== path){
                this.$router.push(this.pagePath + pageNumber);
                this.pageCurrApp = pageNumber;
            }
        },
        getAppData(id, index) {
            if(id) this.appItem.id = id;
            if(index) this.appItem.index = index;
        },
        deleteAppItem(id) {
            if(id) {
                axios.post('/api/event_application_delete/' + this.appItem.id)
                    .then(resp => {
                        if(resp.data.status === 'success'){
                            this.status.error = false;
                            this.getAppList();
                        }else if(resp.data.status === 'empty') {
                            this.status.error = true;
                            this.status.info = "Нет записей для удаления";
                        }
                    });
            }
        }
    }
}
</script>
