<template>
    <div class="modal-video-list modal fade" id="modalEventsList" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card p-3 p-lg-4">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h4">Список мероприятий</h1>
                            </div>
                            <div class="mb-4">
                                <div class="border-bottom modal-video-list__header">
                                    <div>
                                        <input type="checkbox" name="event_item_all" class="form-check-input" v-model="checkAll">
                                    </div>
                                    <span class="me-1">ID</span>
                                    <div class="fw-bold">Наименование</div>
                                    <!-- <div class=""><input type="checkbox" name="all_video_item" class="form-check-input" id="all_video_item" @change="checkAllItem($event)"></div> -->
                                </div>
                                <div
                                    class="border-bottom modal-video-list__item"
                                    v-for="item in dataList"
                                    :key="item.id"
                                >
                                    <input type="checkbox" name="event_item" class="form-check-input" :id="'event_item_' + item.id" :value="item.id" v-model="dataSelected">
                                    <span class="modal-video-list__item-id me-1">{{ item.id }}</span>
                                    <label :for="'event_item_' + item.id" class="m-0 modal-video-list__item-title" :title="item.title">{{ item.title }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-danger px-3 px-lg-4" v-if="dataError">
                            {{ dataError }}
                        </div>
                        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                            <PaginationNav :total="dataTotal" :item="dataCount" @page-changed="loadPageData" />
                        </div>
                        <div class="d-flex justify-content-center mt-2 mb-4">
                            <button class="btn btn-primary" @click.prevent="transferSelected" :data-bs-dismiss="closeModal">Добавить</button>
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
            if (this.dataItems.length > 0) {
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

                for (let i = 0; i < resultData.length; i++) {
                    if (i >= startPage && i < endPage) {
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
            this.$emit('data-selected', this.dataSelected);
        },
        loadPageData(pageNumber) {
            this.currPage = pageNumber
        },
        clearSelected() {
            this.dataSelected = []
        }
    }
    // methods: {
    //     getCheckEvents(){
    //         // console.log(this.checkVideos);
    //         this.$emit('check-events', this.checkEvents)
    //         // this.$emit('close-modal', true)
    //     },
    //     checkAllItem(event){
    //         this.checkEvents = event.target.checked;
    //         // console.log(this.checkVideos);
    //         // this.$emit('check-videos', this.checkVideos)
    //     },
    //     getEventsListNav(pageNumber) {
    //         let start_page = (this.count_events * pageNumber) - this.count_events;
    //         let end_page = pageNumber * this.count_events;
    //         let arr_tests = [];
    //         let n = 0;
    //         for(let i=0; i < this.eventsList.length; i++){
    //             if(i >= start_page && i < end_page){
    //                 n++;
    //                 arr_tests.push(this.eventsList[i]);
    //             }
    //         }
    //         // console.log(arr_tests);
    //         this.newEventsList = arr_tests;
    //         this.curr_count = n;
    //     }
    // }
}
</script>
<style scoped>
.modal-vue {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0;
    transition: opacity 0.15s linear;
}
.modal-video-list__header {
    color:#9d9d9d;
    padding: 12px 10px;
    display: grid;
    grid-template-columns: auto auto 1fr;
    grid-gap: 15px;
}
.modal-video-list__item {
    display: grid;
    grid-template-columns: auto auto 1fr;
    padding: 12px 10px;
    grid-gap: 15px;
}
.modal-video-list__item-title {
    max-height: 24px;
    overflow: hidden;
    padding-right: 15px;
    position: relative;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.modal-video-list__item-id {
    display: inline-block;
    color: #7994bd;
    font-size: 14px;
}
</style>
