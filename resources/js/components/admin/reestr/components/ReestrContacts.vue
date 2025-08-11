<template>
    <div class="modal-video-list modal fade" id="modalReestrContacts" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card p-3 p-lg-4 border-0 pb-0">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <h1 class="mb-0 h4">Список контактов</h1>
                        </div>
                        <div class="">
                            <div class="border-bottom modal-video-list__header d-flex justify-content-between py-3">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <input type="checkbox" name="contacts_item_all" class="form-check-input me-1" v-model="checkAll">
                                    </div>
                                    <div class="fw-bolder">Электронный адрес</div>
                                </div>
                                <div class="">
                                    <a href="" class="text-danger" title="Удалить отмеченные" @click.prevent="deleteSelected" v-if="dataDelete.length > 0">
                                        <i class="fas fa-trash-alt me-1"></i>
                                        <span>Удалить отмеченные</span>
                                    </a>
                                </div>
                            </div>
                            <div
                                class="border-bottom modal-video-list__item py-2 d-flex justify-content-between"
                                v-for="item in dataList"
                                :key="item.id"
                            >
                                <div class="">
                                    <input type="checkbox" name="contact_item" class="form-check-input me-2" :value="item" v-model="dataDelete">
                                    <label :for="'contact_item_' + item.id" class="m-0 modal-video-list__item-title" :title="item.email">{{ item.email }}</label>
                                </div>
                                <div class="">
                                    <a href="" class="text-danger" title="Удалить" @click.prevent="deleteItem(item.id)"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger px-3 px-lg-4" v-if="dataError">
                        {{ dataError }}
                    </div>
                    <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                        <PaginationNav :total="dataTotal" :item="dataCount" @page-changed="loadPageData" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import PaginationNav from "../../components/PaginationNav.vue";

export default {
    components: {
        PaginationNav
    },
    props: {
        dataContacts: Array,
        dataError: String
    },
    emits: [
        'delete-contact-item',
        'delete-contact-data',
        'close-modal'
    ],
    data: () => ({
        dataTotal: 0,
        dataCount: 10,
        currPage: 1,
        dataDelete: [],
        dataListAll: []
    }),
    computed: {
        dataList() {
            if(this.dataContacts.length > 0) {
                let resultData = this.dataContacts;

                // постраничная навигация и разбивка на страницы
                let currPage = this.currPage;
                let pageNumber = 1;
                if (currPage) {
                    pageNumber = currPage
                }
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

                this.dataListAll = resultData;

                return arrApp;
            }
        },
        checkAll: {
            get() {
                return this.dataDelete.length === this.dataListAll.length
            },
            set(checked) {
                this.dataDelete = checked ? [...this.dataListAll] : [];
            }
        }
    },
    methods: {
        deleteItem(id) {
            this.$emit('delete-contact-item', id);
            this.dataDelete = [];
        },
        deleteSelected() {
            const dataDelete = this.dataDelete.map((el)=>{ return el.id })
            this.$emit('delete-contact-data', dataDelete)
        },
        loadPageData(pageNumber) {
            this.currPage = pageNumber
        },
        closeModal() {
            this.$emit('close-modal', true);
            this.dataDelete = [];
        }
    }
}
</script>
