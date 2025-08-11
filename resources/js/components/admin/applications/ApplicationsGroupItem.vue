<template>
    <div class="applications-group-item">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-end pt-2 mb-3">
            <nav-page :path-title="navTitle" :subtitle="titleSection" :path="'/admin/' + sectionApp" />
        </div>
        <div class="d-flex justify-content-between align-items-end mb-4">
            <router-link :to="'/admin/' + sectionApp + '/page/' + pageAppBack" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i>Назад</router-link>
            <div class="btn-toolbar mb-2 mb-md-0">
                <upload-app-data :app-data="appData" :event-id="eventId" />
            </div>
        </div>
        <div class="table-settings mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <app-search @search-req="searchData" />
                <duplicate-filter @check-input="getCheckDuplicateApp" />
            </div>
        </div>
        <applications-list @app-data-list="getAppData" :search-req="searchDataReq" :check-duplicate="checkDuplicateApp" />
    </div>
</template>
<script>
import ApplicationsList from "./ApplicationsList.vue";
import AppSearch from "../components/Search.vue";
import UploadAppData from "./components/UploadAppData.vue";
import NavPage from "../components/NavPage.vue";
import DuplicateFilter from "./components/DuplicateFilter.vue";
import axios from "axios";

export default {
    components: {
        DuplicateFilter,
        ApplicationsList,
        NavPage,
        UploadAppData,
        AppSearch
    },
    props: {
        pageApp: null,
        archive: Boolean
    },
    data: () => ({
        appData: [],
        eventId: null,
        titleSection: "",
        pageAppBack: 1,
        checkDuplicateApp: false,
        sectionApp: 'applications',
        navTitle: 'Заявки',
        searchDataReq: ""
    }),
    mounted() {
        if(this.$route.params.id) this.eventId = parseInt(this.$route.params.id);
        this.getEventTitle(this.$route.params.id);
        if(this.pageApp) this.pageAppBack = this.pageApp;
        if(this.archive) {
            this.sectionApp = 'app_archive';
            this.navTitle = 'Архив заявок';
        }
    },
    methods: {
        getAppData(data) {
            if(data.length !== 0){
                this.appData = data;
            }
        },
        getEventTitle(id) {
            if(id){
                axios.post('/api/event_title/' + id)
                    .then(resp => {
                        if(resp.data.event_title) {
                            this.titleSection = resp.data.event_date_public + ': ' + resp.data.event_title;
                        }
                    }).catch(err => {

                    })
            }
        },
        getCheckDuplicateApp(check) {
            this.checkDuplicateApp = check
        },
        searchData(req) {
            this.searchDataReq = req;
        }
    }
}
</script>
