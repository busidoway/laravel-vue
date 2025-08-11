<template>
    <div class="programs-app-page">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page title="Заявки" :nav-list="dataNav" />
        </div>
        <div class="table-settings mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <app-search @search-req="searchData" />
                <router-link :to="{ name: 'AppArchive' }" class="btn btn-primary"><i class="fas fa-archive me-2"></i><span>Архив заявок</span></router-link>
            </div>
        </div>
        <programs-app-cat />
    </div>
</template>

<script>
import NavPage from "../../components/NavPage.vue";
import ProgramsAppCat from "./ProgramsAppCat.vue";
import AppSearch from "../../components/Search.vue";

export default {
    components: {
        ProgramsAppCat,
        AppSearch,
        NavPage
    },
    data: () => ({
        appData: [],
        searchDataReq: "",
        dataNav: [
            {
                title: 'Заявки',
                active: true
            }
        ]
    }),
    methods: {
        getAppData(data) {
            if(data.length !== 0){
                this.appData = data;
            }
        },
        searchData(req) {
            this.searchDataReq = req;
        }
    }
}
</script>
