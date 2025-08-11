<template>
    <div class="programs-main-list">
        <div class="text-danger mb-3" v-if="status.error">{{ status.info }}</div>
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-gray-400" role="status" v-if="loading">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="card-list">
            <div class="card programs-main-item mb-3" v-for="item in dataList" :key="item.id">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-5">
                            <div class="mb-3 programs-main-item__logo" v-if="item.logo">
                                <a :href="'/org/'+item.org_id" v-if="!item.hidden_more">
                                    <img :src="item.logo" alt="" height="40px">
                                </a>
                                <span v-else>
                                    <img :src="item.logo" alt="" height="40px">
                                </span>
                            </div>
                            <div class="mb-3 programs-main-item__program">
                                {{ item.program }}
                            </div>
                            <div class="fs-5 mb-1 programs-main-item__org" v-if="item.org">
                                <a :href="'/org/'+item.org_id" v-if="!item.hidden_more">{{ item.org }}</a>
                                <span v-else>{{ item.org }}</span>
                            </div>
                            <div class="fs-5 mb-1 programs-main-item__org" v-else-if="item.org_name_short">
                                <a :href="'/org/'+item.org_id" v-if="!item.hidden_more">{{ item.org_name_short }}</a>
                                <span v-else>{{ item.org_name_short }}</span>
                            </div>
                            <div class="d-flex justify-content-between programs-main-item__city-form">
                                <div class="d-flex">
                                    <div class="programs-main-item__city">{{ item.city }}</div>
                                    <div class="px-1">·</div>
                                    <div class="programs-main-item__form-edu">{{ item.form_edu }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 d-flex flex-column align-items-start">
                            <div class="mb-3">
                                <div class="mb-2 programs-main-item__date">
                                    <span class="programs-main-item__date_title">Дата начала занятий:</span>
                                    <span class="programs-main-item__date_text">{{ item.date }}</span>
                                </div>
                                <div class="mb-2 programs-main-item__duration" v-if="item.duration">
                                    <span class="programs-main-item__duration_title">Продолжительность:</span>
                                    <span class="programs-main-item__duration_text">{{ item.duration }}</span>
                                </div>
                                <div class="mb-2 programs-main-item__duration" v-if="item.extension">
                                    <span class="programs-main-item__duration_title">Продление аттестата:</span>
                                    <span class="programs-main-item__duration_text">{{ item.extension }}</span>
                                </div>
                                <div class="mb-2 programs-main-item__price">
                                    <span class="programs-main-item__price_title">Стоимость:</span>
                                    <span class="programs-main-item__price_price">{{ formatNumber(item.price) }}</span>
                                    <span class="ms-0 programs-main-item__price_curr" v-if="item.price">руб.</span>
                                </div>
                                <div class="programs-main-item__duration" v-if="item.tp_price_exam">
                                    <span class="programs-main-item__duration_title">Стоимость экзамена:</span>
                                    <span class="programs-main-item__duration_text text-success" v-if="item.price_exam">включена</span>
                                    <span class="programs-main-item__duration_text" v-else>не включена</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-3 d-flex flex-column justify-content-center align-content-end">
                            <div class="programs-main-item__button-content text-xl-right text-center">
                                <button
                                    class="btn btn-primary programs-main-item__btn btn-modal"
                                    :data-id="item.org_id"
                                    :data-program-id="item.id"
                                    :data-title="item.program + ' ' + '(' + item.date + ')'"
                                    :data-header="'Заявка на ' + item.program + ' ' + '(' + item.date + ').' + ' ' + item.org"
                                    data-recaptcha-id="recaptcha_program_edu"
                                    data-bs-toggle="modal"
                                    data-bs-target="#formEventModal">Подать заявку</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between mt-3" v-if="!loading">
            <pagination-nav :total="dataTotal" :item="dataCount" :active-page="pageCurr" @page-changed="loadPageData" />
        </div>
    </div>
</template>

<script>
import PaginationNav from "../../admin/components/PaginationNav.vue";
import axios from "axios";

export default {
    name: 'ProgramsMainList',
    components: {
        PaginationNav
    },
    props: {
        cityId: Number,
        orgId: Number,
        programId: Number,
        programGroupId: Number,
        formEduId: Number,
        dateStart: Array,
        typeProgramId: Number,
        typeProgramCode: String,
        setFilter: Boolean
    },
    data: () => ({
        dataArr: [],
        dataTotal: 0,
        dataCount: 10,
        pagePath: '/programs/',
        pageCurr: 1,
        loading: true,
        status: {
            success: false,
            error: false,
            info: ""
        },
    }),
    watch: {
        '$route.params': 'getDataList'
    },
    computed: {
        dataList() {
            let resultData = [];

            resultData = this.dataArr;

            if(this.typeProgramId) {
                resultData = resultData.filter((el) => {
                    return el.type_program_id === this.typeProgramId
                })
            }

            if(this.cityId) {
                resultData = resultData.filter((el) => {
                    return el.city_id === this.cityId
                })
            }

            if(this.orgId) {
                resultData = resultData.filter((el) => {
                    return el.org_id === this.orgId
                })
            }

            if(this.programId) {
                resultData = resultData.filter((el) => {
                    return el.program_id === this.programId
                })
            }

            if(this.programGroupId) {
                resultData = resultData.filter((el) => {
                    return el.group_id === this.programGroupId
                })
            }

            if(this.formEduId) {
                resultData = resultData.filter((el) => {
                    return el.form_edu_id === this.formEduId
                })
            }

            if(this.dateStart.length > 0) {
                if(this.dateStart[0] && this.dateStart[1]) {
                    resultData = resultData.filter((el) => {
                        return el.date >= this.dateStart[0] && el.date <= this.dateStart[1]
                    })
                }
            }

            // постраничная навигация и разбивка на страницы
            let currPage = parseInt(this.$route.params.page);
            let pageNumber = 1;

            // if(this.setFilter) {
            //     pageNumber = 1;
            //     resultData = this.dataArr;
            //     this.$emit('unset-filter', true);
            // }

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
            axios.get('/api/programs_edu_all')
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
            let path = this.pagePath + this.typeProgramCode + '/' + pageNumber;
            this.pageCurr = pageNumber;

            if (this.$route.path !== path){
                // this.$router.push(this.pagePath + this.typeProgramCode + '/' + pageNumber);
                this.$router.push(path);
            }
        },
        formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        }
    }
}
</script>

