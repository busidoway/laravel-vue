<template>
    <div class="applications-page">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page title="Архив заявок" :nav-list="dataNav" />
        </div>
        <div class="table-settings mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <app-search @search-req="searchData" />
                <router-link :to="{ name: 'Apps' }" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i><span>Назад</span></router-link>
            </div>
        </div>
        <applications-group @app-data-list="getAppData" :app-archive="true" :search-req="searchDataReq" />
    </div>
</template>

<script>
import NavPage from "../components/NavPage.vue";
import ApplicationsGroup from "./ApplicationsGroup.vue";
import AppSearch from "../components/Search.vue";

export default {
    components: {
        AppSearch,
        ApplicationsGroup,
        NavPage
    },
    data: () => ({
        appData: [],
        searchDataReq: "",
        dataNav: [
            {
                title: 'Заявки',
                path: '/admin/applications/'
            },
            {
                title: 'Архив заявок',
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
