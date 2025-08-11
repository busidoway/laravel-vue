<template>
    <div class="modal-event-sub-categories modal-items modal fade" id="modalEventSubCategories" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card p-3 p-lg-4 border-0 pb-0">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <h1 class="mb-0 h4">Список подкатегорий</h1>
                        </div>
                        <div class="">
                            <div class="border-bottom modal-list-header d-flex justify-content-between py-3">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <input type="checkbox" id="modal_item_check_all" name="contacts_item_all" class="form-check-input me-1" v-model="checkAll">
                                    </div>
                                    <div class="fw-bolder"><label for="modal_item_check_all">Выбрать все</label></div>
                                </div>
                                <div class="">
                                    <a href="" class="text-danger" title="Сбросить выбранное" @click.prevent="clearSelected" v-if="dataSelected.length > 0">
                                        <i class="far fa-minus-square"></i>
                                        <span>Сбросить выбранное</span>
                                    </a>
                                </div>
                            </div>
                            <div
                                class="border-bottom modal-list-item py-2 d-flex justify-content-between"
                                v-for="item in dataList"
                                :key="item.id"
                            >
                                <div class="">
                                    <input type="checkbox" name="item" :id="'modal_item_' + item.id" class="form-check-input me-2" :value="item" v-model="dataSelected">
                                    <label :for="'modal_item_' + item.id" class="m-0 modal-list-item__title" :title="item.title">{{ item.title }}</label>
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
                    <div class="d-flex justify-content-center mt-2 mb-2">
                        <button class="btn btn-primary" @click.prevent="transferSelected" :data-bs-dismiss="closeModal">Добавить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import PaginationNav from "../../../components/PaginationNav.vue";
export default {
    name: "EventSubCategoriesModal",
    components: {
        PaginationNav
    },
    props: {
        dataItems: {
            type: Array,
            default: []
        },
        dataError: String
    },
    emits: [
        'data-selected'
    ],
    data: () => ({
        dataTotal: 0,
        dataCount: 10,
        currPage: 1,
        dataListAll: [],
        dataSelected: [],
        closeModal: "modal"
    }),
    computed: {
        dataList() {
            // console.log('dataItems:', this.dataItems.length, this.dataItems)
            if(this.dataItems.length > 0) {
                let resultData = this.dataItems;

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
            return [];
        },
        checkAll: {
            get() {
                return this.dataSelected.length === this.dataListAll.length
            },
            set(checked) {
                this.dataSelected = checked ? [...this.dataListAll] : [];
            }
        }
    },
    methods: {
        transferSelected() {
            // console.log('dataSelected:', this.dataSelected);
            this.$emit('data-selected', this.dataSelected);
        },
        loadPageData(pageNumber) {
            this.currPage = pageNumber
        },
        clearSelected() {
            this.dataSelected = []
        }
    }
}
</script>
