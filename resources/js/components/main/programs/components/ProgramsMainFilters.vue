<template>
    <div class="programs-main-filters">
        <city-filter @city-id="getCityId" :clear="btnFilter" />
        <organization-filter @org-id="getOrgId" :clear="btnFilter" />
        <program-filter @program-id="getProgramId" @program-group-id="getProgramGroupId" :clear="btnFilter" />
        <form-education-filter @form-edu-id="getFormEduId" :clear="btnFilter" />
        <date-filter @date-start-val="getDateStart" :clear="btnFilter" />
        <div class="clear-filter" v-if="btnFilter">
            <button class="btn" @click.prevent="setClearFilter">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </div>
</template>

<script>
import CityFilter from "./filters/CityFilter.vue";
import OrganizationFilter from "./filters/OrganizationFilter.vue";
import ProgramFilter from "./filters/ProgramFilter.vue";
import FormEducationFilter from "./filters/FormEducationFilter.vue";
import DateFilter from "./filters/DateFilter.vue";
export default {
    name: 'ProgramsMainFilters',
    props: {
        clearAllFilters: Boolean
    },
    data: () => ({
        btnFilter: false,
        clearFilter: false,
        setFilter: false
    }),
    components: {
        OrganizationFilter,
        ProgramFilter,
        FormEducationFilter,
        DateFilter,
        CityFilter
    },
    watch: {
        clearFilter(newVal) {
            if (newVal) {
                this.btnFilter = false;
            }
        },
        clearAllFilters(newVal) {
            if (newVal) {
                this.btnFilter = false;
                // Вызываем метод для сброса фильтров
                this.setClearFilter();
            }
        }
    },
    methods: {
        getCityId(id) {
            // this.btnFilter = false;
            this.$emit("city-id", id);
            this.btnFilter = true;
        },
        getOrgId(id) {
            this.$emit("org-id", id);
            this.btnFilter = true;
        },
        getProgramId(id) {
            this.$emit("program-id", id);
            this.btnFilter = true;
        },
        getProgramGroupId(id) {
            this.$emit("program-group-id", id);
            this.btnFilter = true;
        },
        getFormEduId(id) {
            this.$emit("form-edu-id", id);
            this.btnFilter = true;
        },
        getDateStart(date) {
            this.$emit("date-start-val", date);
            this.btnFilter = true;
        },
        setClearFilter() {
            this.$emit('clear-filter');
            this.btnFilter = false;
        },
    }
}
</script>
