<template>
    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <div class="text-danger mb-3" v-if="status.error">{{ status.info }}</div>
        <div class="">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="" id="check_all" v-model="checkAll" title="Выбрать все"><label class="ms-2" for="check_all">Выбрать все</label>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-gray-400" role="status" v-if="loading">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table class="table table-app table-reestr table-hover">
            <thead>
            <tr>
                <th class="border-gray-200">
                <span>
                    <input type="checkbox" class="form-check-input" name="" id="check_list" v-model="checkList" title="Выбрать все">
                </span>
                </th>
                <th class="border-gray-200">№ аттестата</th>
                <th class="border-gray-200">ФИО</th>
                <th class="border-gray-200">Город</th>
                <th class="border-gray-200">Регион</th>
                <th class="border-gray-200">E-mail</th>
                <th class="border-gray-200">Член/запись</th>
                <th class="border-gray-200">Публ. в реестре</th>
                <th class="border-gray-200">Оплата членства</th>
                <th class="border-gray-200">Окончание аттестата</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in dataList" :key="item.id">
                <td data-label="Выбрать">
                <span>
                    <input type="checkbox" class="form-check-input" name="" id="" v-model="dataChecked[pageCurr]" :value="item">
                </span>
                </td>
                <td data-label="№ аттестата">
                    <span>{{ item.num_doc }}</span>
                </td>
                <td data-label="ФИО">
                    <span>{{ item.name }}</span>
                </td>
                <td data-label="Город">
                    <span>{{ item.city }}</span>
                </td>
                <td data-label="Регион">
                    <span>{{ item.region }}</span>
                </td>
                <td data-label="E-mail">
                    <span>{{ item.email }}</span>
                </td>
                <td data-label="Член/запись">
                    <span class="text-success" v-if="item.membership && item.membership == 1">Членство</span>
                    <span class="text-info" v-else>Запись</span>
                </td>
                <td data-label="Публ. в реестре">
                    <div v-if="item.payment_reestr.length !== 0">
                        <div v-for="pr in item.payment_reestr">
                            <div v-if="pr.name == 'reestr'" class="d-flex align-items-center table-reestr-pay-item">
                                <span :class="[pr.status === 1 ? 'text-success' : '', pr.status === 0 ? 'text-danger' : '', 'app-payment-item']">{{ pr.year }}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td data-label="Оплата членства">
                    <div v-if="item.payment_reestr.length !== 0">
                        <div v-for="pr in item.payment_reestr">
                            <div v-if="pr.name == 'membership'" class="d-flex align-items-center table-reestr-pay-item">
                                <span :class="[pr.status === 1 ? 'text-success' : '', pr.status === 0 ? 'text-danger' : '', 'app-payment-item']">{{ pr.year }}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td data-label="Окончание аттестата">
                    <span :class="[item.expired == 1 ? 'bg-danger text-white' : '', item.date_coming == 1 ? 'bg-warning' : '', 'reestr-date-end']">{{ item.date_end }}</span>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between" v-if="!loading">
            <PaginationNav :total="dataTotal" :item="dataCount" :active-page="pageCurr" @page-changed="loadPageData" />
        </div>
        <modal-delete :id="dataItem.id" :title="dataItem.num_doc + dataItem.index + ' и все внутренние заявки'" @delete-confirm="deleteDataItem" />
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
        searchReq: String,
        filterDateStart: String,
        filterDateEnd: String,
        statusPayment: Array,
        statusDoc: Array,
        statusMember: String,
        setFilter: Boolean
    },
    emits: [
        'data-list',
        'unset-filter',
        'count-selected'
    ],
    data: ()=> ({
        dataArr: [],
        dataTotal: 0,
        dataCount: 10,
        dataChecked: {},
        dataListAll: [],
        // checkAll: false,
        page: 1,
        pagePath: '/admin/reestr/page/',
        dataItem: {
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
        pageCurr: 1
    }),
    computed: {
        dataList() {
            let resultData = [];
            // поиск
            let search = this.searchReq.toLowerCase().trim().split(/\s/).filter( (el) => {
                return el != '';
            });
            // this.statusPaymentLocal = this.statusPayment;
            let filterDateStart = this.filterDateStart;
            let filterDateEnd = this.filterDateEnd;
            let statusPayment = this.statusPayment;
            let statusDoc = this.statusDoc;
            let statusMember = this.statusMember;

            if (search.length > 0) {
                let searchStr = search.join(" ");
                resultData = this.dataArr.filter((elem) => {
                    const numDoc = String(elem.num_doc);
                    return (elem.name && elem.name.toLowerCase().trim().indexOf(searchStr) !== -1)
                        || (elem.city && elem.city.trim().toLowerCase().indexOf(searchStr) !== -1)
                        || (elem.region && elem.region.trim().toLowerCase().indexOf(searchStr) !== -1)
                        || (elem.email && elem.email.trim().toLowerCase().indexOf(searchStr) !== -1)
                        || numDoc.trim().toLowerCase().indexOf(searchStr) !== -1
                })
            } else {
                resultData = this.dataArr
            }

            if (filterDateStart !== "") {
                resultData = resultData.filter((el) => {
                    return el.date_start_init >= filterDateStart
                })
            }

            if (filterDateEnd !== "") {
                resultData = resultData.filter((el) => {
                    return el.date_end_init <= filterDateEnd
                })
            }

            if (statusPayment.length > 0) {
                let newData = [];

                for (let i = 0; i < resultData.length; i++) {
                    for (let n = 0; n < statusPayment.length; n++) {
                        if (resultData[i].id === statusPayment[n].id) {
                            newData.push(resultData[i])
                        }
                    }
                }
                resultData = newData;
            }

            if (statusDoc.length > 0) {
                resultData = resultData.filter((el) => {
                    return (statusDoc.includes('1') && !el.expired && !el.date_coming)
                        || (statusDoc.includes('2') && el.date_coming && !el.expired)
                        || (statusDoc.includes('3') && el.expired)
                })
            }

            // console.log(resultData)

            if (statusMember.length > 0) {
                resultData = resultData.filter((el) => {
                    if (el.membership && el.membership === 1 && statusMember.indexOf("membership") !== -1) {
                        return el
                    }else if (!el.membership && statusMember.indexOf("reestr") !== -1) {
                        return el
                    }else if (statusMember.indexOf("0") !== -1) {
                        return el
                    }
                })
            }

            if (resultData.length > 0) {
                // this.$emit('data-list', dataItemsId);
                this.dataListAll = resultData;
            }

            // постраничная навигация и разбивка на страницы
            let currPage = parseInt(this.$route.params.page);
            let pageNumber = 1;

            if (currPage) {
                pageNumber = currPage;
            }

            if (this.setFilter) {
                pageNumber = 1;
                this.$emit('unset-filter', true);
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

            return arrApp;
        },
        checkAll: {
            get() {
                // const count = Object.values(this.dataChecked).reduce((acc, pageItems) => acc + pageItems.length, 0);
                // return count === this.dataListAll.length
                // return this.dataChecked.length === this.dataList.length
                const totalChecked = Object.values(this.dataChecked).reduce(
                    (acc, pageItems) => acc + pageItems.length,
                    0
                );
                return totalChecked === this.dataListAll.length;
            },
            set(checked) {
                // this.dataChecked = checked ? [...this.dataListAll] : [];
                // this.$set(this.dataChecked, this.pageCurr, checked ? [...this.dataList] : []);

                // добавляем все элементы со всех страниц в dataChecked
                if (checked) {
                    const allChecked = {};
                    this.dataListAll.forEach((item, index) => {
                        const page = Math.floor(index / this.dataCount) + 1; // Определяем страницу
                        if (!allChecked[page]) allChecked[page] = [];
                        allChecked[page].push(item);
                    });
                    this.dataChecked = allChecked;
                } else {
                    // Если checked = false, очищаем dataChecked
                    this.dataChecked = {};
                }
            }
        },
        checkList: {
            get() {
                // return this.dataChecked.length === this.dataList.length
                return (
                    this.pageCurr in this.dataChecked &&
                    this.dataChecked[this.pageCurr].length === this.dataList.length
                );
            },
            set(checked) {
                // this.dataChecked = checked ? [...this.dataList] : [];
                // this.dataChecked[this.pageCurr] = checked ? [...this.dataList] : [];
                this.$set(this.dataChecked, this.pageCurr, checked ? [...this.dataList] : []);
            }
        }
    },
    watch: {
        // dataChecked(value) {
        //     const count = this.dataChecked.length;
        //     this.$emit('count-selected', count);
        //     this.$emit('data-list', value);
        // }
        dataChecked: {
            deep: true,
            handler(newVal) {
                const count = Object.values(newVal).reduce((acc, pageItems) => acc + pageItems.length, 0);
                this.$emit('count-selected', count);
                this.$emit('data-list', newVal);
            }
        }
    },
    mounted() {
        this.getDataList();
    },
    methods: {
        getDataList() {
            let currPage = parseInt(this.$route.params.page);
            // this.pageCurr = parseInt(currPage);
            axios.get('/api/reestr_list')
                .then(resp => {
                    if(resp.data) {
                        let respData = resp.data.result;
                        this.status.error = false;
                        this.loading = false;
                        this.dataArr = respData;
                        this.dataTotal = respData.length;
                        // this.$emit('data-list', respData);
                        if (currPage) {
                            this.loadPageData(currPage)
                        } else if (this.dataTotal > this.dataCount) {
                            this.loadPageData(1)
                        }
                    }
                }).catch(err => {
                this.status.error = true;
                this.status.info = err;
            })
        },
        getItemData(id, index) {
            if(id) this.dataItem.id = id;
            if(index) this.dataItem.index = index;
        },
        deleteDataItem(id) {
            if(id) {
                axios.post('/api/reestr_delete/' + this.dataItem.id)
                    .then(resp => {
                        if(resp.data.status){
                            this.status.error = false;
                            this.getDataList();
                        }else{
                            this.status.error = true;
                            this.status.info = "Ошибка удаления";
                        }
                    });
            }
        },
        loadPageData(pageNumber) {
            this.pageCurr = pageNumber;

            if (!(this.pageCurr in this.dataChecked)) {
                // this.dataChecked[pageNumber] = [];
                this.$set(this.dataChecked, this.pageCurr, []);
            }

            let path = this.pagePath + pageNumber;
            if (this.$route.path !== path){
                this.$router.push(path);

            }
        }
    }
}
</script>

<style>
.reestr-date-end {
    padding: 2px;
    border-radius: 4px;
    background-color: transparent;
}
</style>
