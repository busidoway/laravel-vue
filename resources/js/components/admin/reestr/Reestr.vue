<template>
    <div class="applications-page">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page title="Реестр" :nav-list="dataNav" />
            <div class="">
                <RouterLink class="btn btn-success text-white" :to="{ name: 'ReestrMail' }">
                    <i class="fas fa-envelope-open-text"></i>
                    <span class="ms-1">Отправить рассылку</span>
                </RouterLink>
            </div>
        </div>
        <div class="filter-section table-settings mb-4 d-flex align-items-center justify-content-between">
            <div class="filter-section-container d-flex align-items-center flex-md-row flex-column">
                <reestr-search
                    @search-req="searchData"
                    :clear="clearFilter"
                />
                <range-date
                    class="ms-3"
                    @date-start-val="filterDateStart"
                    @date-end-val="filterDateEnd"
                    :clear="clearFilter"
                />
                <doc-status
                    class="ms-3"
                    @status="getStatusDoc"
                    :clear="clearFilter"
                />
                <member-status
                    class="ms-3"
                    @status="getStatusMember"
                    :clear="clearFilter"
                />
                <payment-status
                    class="ms-3"
                    @status="getStatusPayment"
                    :clear="clearFilter"
                />
                <div class="button-clear ms-3">
                    <button class="btn btn-gray-300" @click.prevent="setClearFilter">Сбросить</button>
                </div>
            </div>
            <div class="">
                <div class="" v-if="countSelected">
                    <span class="me-1">Выбрано:</span>
                    <span class="text-danger fw-bold d-inline-block">{{ countSelected }}</span>
                </div>
            </div>
        </div>
        <ReestrList
            :data-list="dataItems"
            :search-req="searchDataReq"
            :filter-date-start="dateStart"
            :filter-date-end="dateEnd"
            :status-payment="statusPayment"
            :status-doc="statusDoc"
            :status-member="statusMember"
            :set-filter="setFilter"
            @count-selected="getCountSelected"
            @data-list="getData"
            @unset-filter="unsetFilter"
        />
    </div>
</template>

<script>
import NavPage from "../components/NavPage.vue";
import ReestrList from "./ReestrList.vue";
import ReestrSearch from "../components/Search.vue";
import RangeDate from "./components/RangeDate.vue";
import PaymentStatus from "./components/PaymentStatus.vue";
import DocStatus from "./components/DocStatus.vue";
import MemberStatus from "./components/MemberStatus.vue";
import axios from "axios";
import { mapActions ,mapState } from "vuex";

export default {
    components: {
        MemberStatus,
        NavPage,
        ReestrList,
        ReestrSearch,
        RangeDate,
        PaymentStatus,
        DocStatus
    },
    data: () => ({
        dataNav: [
            {
                title: 'Реестр',
                active: true
            }
        ],
        // dataItems: "",
        searchDataReq: "",
        dateStart: "",
        dateEnd: "",
        statusPayment: [],
        statusDoc: [],
        statusMember: "",
        clearFilter: false,
        setFilter: false,
        countSelected: 0
    }),
    computed: {
        ...mapState({
            dataItems: state => state.reestr.dataItems
        })
    },
    mounted() {
        // sessionStorage.removeItem('dataReestrItems');
    },
    methods: {
        ...mapActions(['setDataItems']),
        getData(data) {
            // console.log(data);
            const dataArray = Array.isArray(data) ? data : Object.values(data).flat();

            if (dataArray.length !== 0) {
                const dataItemsId = dataArray.map(el => ({ id: el.id }));
                this.setDataItems(dataItemsId);
            }
        },
        searchData(req) {
            this.clearFilter = false;
            this.searchDataReq = req;
            this.setFilter = true;
        },
        filterDateStart(date) {
            this.clearFilter = false;
            this.dateStart = date;
            this.setFilter = true;
        },
        filterDateEnd(date) {
            this.clearFilter = false;
            this.dateEnd = date;
            this.setFilter = true;
        },
        getStatusMember(val) {
            this.clearFilter = false;
            if(val){
                this.statusMember = val;
                this.setFilter = true;
            }
        },
        getStatusPayment(val) {
            this.clearFilter = false;
            this.setFilter = true;
            const formData = new FormData();
            const statusPayment = JSON.stringify(val);
            formData.append('payment_status', statusPayment);

            axios.post('/api/payment_reestr', formData)
                .then(resp => {
                    if(resp.data) {
                        this.statusPayment = resp.data.reestr;
                    }
                }).catch(err => {
                console.log(err)
            })
        },
        getStatusDoc(val) {
            this.clearFilter = false;
            if(val){
                this.statusDoc = val;
                this.setFilter = true;
            }
        },
        getCountSelected(val) {
            this.countSelected = val;
        },
        setClearFilter() {
            this.clearFilter = true;
            this.setFilter = false;
            // Сброс фильтров
            this.$emit('clear');
            this.searchDataReq = "";
            this.dateStart = "";
            this.dateEnd = "";
            this.statusPayment = [];
            this.statusDoc = [];
            this.statusMember = "";
            // сбрасываем clearFilter в false, чтобы можно было повторно срабатывать при следующем нажатии.
            this.$nextTick(() => {
                this.clearFilter = false;
            });
        },
        unsetFilter(val) {
            if(val) this.setFilter = false;
        }
    }
}
</script>
