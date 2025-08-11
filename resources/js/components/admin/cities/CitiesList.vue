<template>
    <div class="cities-list">
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
                    <th class="border-gray-200">Наименование</th>
                    <th class="border-gray-200"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in dataList" :key="item.id">
                    <td data-label="ID" class="table-app__id">
                        <span>{{ item.id }}</span>
                    </td>
                    <td data-label="Наименование">
                        <span>{{ item.name }}</span>
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
                                <router-link class="dropdown-item" :to="{ name: 'CitiesEdit', params: { id: item.id } }"><span class="fas fa-edit me-2"></span>Редактирование</router-link>
                                <button type="button" class="dropdown-item text-danger rounded-bottom" data-bs-toggle="modal" data-bs-target="#modal-delete" @click.prevent="getDataItem(item.id, item.name)">
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
            <modal-delete :id="dataItem.id" :title="`'${dataItem.title}'`" @delete-confirm="deleteDataItem" />
        </div>
    </div>
</template>

<script>
import PaginationNav from "../components/PaginationNav.vue";
import ModalDelete from "../components/ModalDelete.vue";
import axios from "axios";

export default {
    components: {
        ModalDelete,
        PaginationNav
    },
    data: () => ({
        dataArr: [],
        dataItem: {
            id: null,
            title: ""
        },
        dataTotal: 0,
        dataCount: 10,
        pagePath: '/admin/cities/page/',
        pageCurr: 0,
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

            for(let i = 0; i < resultData.length; i++){
                if(i >= startPage && i < endPage){
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
            axios.get('/api/cities')
                .then(response => {
                    this.status.error = false;
                    this.status.success = true;
                    this.dataArr = response.data;
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
            let path = this.pagePath + pageNumber;
            this.pageCurr = pageNumber;

            if (this.$route.path !== path){
                this.$router.push(this.pagePath + pageNumber);

            }
        },
        getDataItem(id, name) {
            this.dataItem.id = id;
            this.dataItem.title = name;
        },
        deleteDataItem(id) {
            if(id) {
                axios.delete(`/api/cities_delete/${id}`)
                    .then(response => {
                        if(response.data.status === true) {
                            this.status.error = false;
                            this.status.success = true;
                            this.getDataList();
                        }else{
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
