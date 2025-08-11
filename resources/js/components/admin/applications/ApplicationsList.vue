<template>
    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <div class="text-danger" v-if="status.error">{{ status.info }}</div>
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-gray-400" role="status" v-if="loading">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table class="table table-app table-hover">
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
            <tr v-for="item in appListData">
                <td class="table-app__id">
                    <a href="#" class="fw-bold">
                        {{ item.id }}
                    </a>
                </td>
                <td>
                    <span class="fw-normal">{{ item.last_name_sender }} {{ item.name_sender }} {{ item.middle_name_sender }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ item.email_sender }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ item.phone_sender }}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center" v-if="item.status === 0" title="Ошибка отправки">
                        <i class="fas fa-exclamation-circle text-danger me-1"></i>
                        <span class="text-danger fw-normal">{{ item.date_send }}</span>
                    </div>
                    <div class="d-flex align-items-center" v-else>
                        <span class="fw-normal">{{ item.date_send }}</span>
                    </div>
                </td>
                <td>
                    <div class="" v-if="item.reestr.length !== 0">
                        <span class="text-success" v-if="item.reestr.membership == 1">Членство</span>
                        <span class="text-info" v-else>Запись</span>
                    </div>
                    <div v-else-if="item.intern.length !== 0">
                        <span class="fw-bolder">{{ item.intern.position }}</span>
                    </div>
                    <div v-else>
                        <span class="text-danger">Чужой</span>
                    </div>
                </td>
                <td>
                    <div v-if="item.payment_reestr.length !== 0">
                        <div v-for="pr in item.payment_reestr">
                            <div v-if="pr.name === 'reestr'" class="d-flex align-items-center">
                                <span :class="[pr.status === 1 ? 'text-success' : '', pr.status === 0 ? 'text-danger' : '', 'app-payment-item']">{{ pr.year }}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div v-if="item.payment_reestr.length !== 0">
                        <div v-for="pr in item.payment_reestr">
                            <div v-if="pr.name === 'membership'" class="d-flex align-items-center">
                                <span :class="[pr.status === 1 ? 'text-success' : '', pr.status === 0 ? 'text-danger' : '', 'app-payment-item']">{{ pr.year }}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
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
            <pagination-nav :total="appTotal" :item="appCount" @page-changed="loadPageApp" />
        </div>
        <modal-delete :id="appItem.id" :title="appItem.title + appItem.id" @delete-confirm="deleteAppItem" />
    </div>
</template>

<script>
import axios from "axios";
import PaginationNav from "../components/PaginationNav.vue";
import ModalDelete from "../components/ModalDelete.vue";

export default {
    components: {
        PaginationNav,
        ModalDelete
    },
    props: {
        checkDuplicate: Boolean,
        searchReq: String
    },
    emits: [
        'app-data-list'
    ],
    data: () => ({
        appData: [],
        appTotal: 0,
        appCount: 10,
        page: 1,
        appItem: {
            id: null,
            title: "запись № "
        },
        loading: true,
        status: {
            success: false,
            error: false,
            info: ""
        }
    }),
    mounted() {
        this.getAppList();
        this.loadPageApp(this.page);
    },
    watch: {
        checkDuplicate: function (val) {
            this.getAppList(val)
        }
    },
    computed: {
        appListData() {
            let resultData = [];
            // поиск по email, фамилии, имени и отчеству
            let search = this.searchReq.toLowerCase().trim().split(/\s/).filter( (el) => {
                return el != '';
            });
            if(search.length === 1) {
                resultData = this.appData.filter( (elem) => {
                        return elem.email_sender.toLowerCase().indexOf(search) !== -1
                            || elem.last_name_sender.toLowerCase().indexOf(search) !== -1
                            || elem.name_sender.toLowerCase().indexOf(search) !== -1
                            || elem.middle_name_sender.toLowerCase().indexOf(search) !== -1;
                    })
            }else if(search.length > 1) {
                resultData = this.appData.filter( (elem) => {
                    let searchStr = search.join(" ");
                    let fullName = elem.last_name_sender.trim() + " " + elem.name_sender.trim() + " " + elem.middle_name_sender.trim();

                    if(fullName.toLowerCase().indexOf(searchStr) !== -1){
                        return elem
                    }
                })
            }else{
                resultData = this.appData
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

            // console.log(arrApp)

            return arrApp;
        }
    },
    methods: {
        getAppList(check) {
            let currPage = this.$route.params.page;
            let currEventId = this.$route.params.id;
            axios.post('/api/applications_list/' + currEventId)
                .then(resp => {
                    if(resp.data) {
                        let respData = [];
                        if(!check){
                            // удаление дубликатов
                            respData = resp.data.applications.filter((obj, idx, arr) =>
                                idx === arr.findIndex((t) => t.email_sender === obj.email_sender));
                        }else{
                            respData = resp.data.applications;
                        }
                        this.status.error = false;
                        this.loading = false;
                        this.appData = respData;
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
            let currEventId = this.$route.params.id;
            let path = '/admin/applications/' + currEventId + '/page/' + pageNumber;

            if (this.$route.path !== path){
                this.$router.push('/admin/applications/' + currEventId + '/page/' + pageNumber);
            }
        },
        getAppData(id) {
            if(id) this.appItem.id = id;
        },
        deleteAppItem(id) {
            if(id) {
                axios.post('/api/application_delete/' + this.appItem.id)
                    .then(resp => {
                        if(resp.data.status === 'success'){
                            this.status.error = false;
                            this.getAppList();
                        }else if(resp.data.status === 'error') {
                            this.status.error = true;
                            this.status.info = "Ошибка удаления";
                        }
                    });
            }
        }
    }
}
</script>
