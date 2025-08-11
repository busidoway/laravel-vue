<template>
    <div class="programs-main">
        <programs-main-cat
            @type-program-id="getTypeProgram"
            @type-program-code="getTypeProgramCode"
        />
        <programs-main-filters
            @city-id="getCityId"
            @org-id="getOrgId"
            @program-id="getProgramId"
            @program-group-id="getProgramGroupId"
            @form-edu-id="getFormEduId"
            @date-start-val="getDateStart"
            @clear-filter="clearAllFilters"
            :clear-all-filters="setClearFilters"
        />
        <div class="programs-main__content">
            <programs-main-list
                :city-id="cityId"
                :org-id="orgId"
                :program-id="programId"
                :program-group-id="programGroupId"
                :form-edu-id="formEduId"
                :date-start="dateStart"
                :type-program-id="typeProgramId"
                :type-program-code="typeProgramCode"
                :set-filter="clearFilter"
            />
        </div>
    </div>
</template>

<script>
import ProgramsMainList from "./ProgramsMainList.vue";
import ProgramsMainFilters from "./components/ProgramsMainFilters.vue";
import ProgramsMainCat from "./components/ProgramsMainCat.vue";
export default {
    name: 'ProgramsMain',
    components: {
        ProgramsMainFilters,
        ProgramsMainList,
        ProgramsMainCat
    },
    data: () => ({
        cityId: null,
        orgId: null,
        programId: null,
        programGroupId: null,
        formEduId: null,
        dateStart: [],
        typeProgramId: null,
        typeProgramCode: "",
        clearFilter: false,
        setClearFilters: false,
        currentPage: 1
    }),
    provide() {
        const vm = this; // Сохраняем ссылку на экземпляр компонента
        return {
            dataProgramsMain: {
                get typeProgramId() {
                    return vm.typeProgramId;
                },
                set typeProgramId(value) {
                    vm.typeProgramId = value;
                },
            }
        };
    },
    created() {
        this.loadRouteParams();
    },
    watch: {
        '$route.params': 'loadRouteParams'
    },
    methods: {
        loadRouteParams() {
            this.typeProgramCode = this.$route.params.code;
            this.currentPage = parseInt(this.$route.params.page) || 1;
        },
        getCityId(id) {
            if(id) this.cityId = id;
            this.updateFilterState();
        },
        getOrgId(id) {
            if(id) this.orgId = id;
            this.updateFilterState();
        },
        getProgramId(id) {
            if(id) this.programId = id;
            this.programGroupId = null;
            this.updateFilterState();
        },
        getProgramGroupId(id) {
            if(id) this.programGroupId = id;
            this.programId = null;
            this.updateFilterState();
        },
        getFormEduId(id) {
            if(id) this.formEduId = id;
            this.updateFilterState();
        },
        getDateStart(date) {
            this.dateStart = date;
            this.updateFilterState();
        },
        getTypeProgram(id) {
            if(id) {
                this.typeProgramId = id;
                this.clearAllFilters();
                this.setClearFilters = true;

                this.$nextTick(() => {
                    this.setClearFilters = false;
                });
            }
            this.updateFilterState();
        },
        getTypeProgramCode(code) {
            if(code) this.typeProgramCode = code;
        },
        clearAllFilters() {
            this.cityId = null;
            this.orgId = null;
            this.programId = null;
            this.programGroupId = null;
            this.formEduId = null;
            this.dateStart = [];
            // this.typeProgramId = null;
            this.clearFilter = true;
            this.$nextTick(() => {
                this.clearFilter = false;
            });
        },
        updateFilterState() {
            this.clearFilter = !!(this.cityId || this.orgId || this.programId || this.programGroupId || this.formEduId || this.dateStart.length);
        }
    }
}
</script>

