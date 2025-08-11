<template>
    <div class="org-filter programs-main-filters__item">
        <div class="dropdown">
            <button class="btn btn-filter dropdown-toggle" type="button" id="org_filter" data-bs-toggle="dropdown" aria-expanded="false">
                <span>{{ titleBtn }}</span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="org_filter">
                <li v-for="item in dataList" :key="item.id">
                    <a v-if="item.name_filter" class="dropdown-item" href="#" @click.prevent="selectItem(item)">{{ item.name_filter }}</a>
                    <a v-else-if="item.name_short" class="dropdown-item" href="#" @click.prevent="selectItem(item)">{{ item.name_short }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'OrganizationFilter',
    props: {
        clear: Boolean
    },
    data: () => ({
        dataList: [],
        titleBtn: "Образовательная организация",
        typeProgram: null
    }),
    inject: ['dataProgramsMain'],
    watch: {
        'dataProgramsMain.typeProgramId': {
            handler(newValue) {
                this.typeProgram = newValue
                this.getDataList();
            },
            immediate: true // Немедленно выполните обработчик с текущим значением
        },
        clear: function (val) {
            if(!val) {
                this.titleBtn = "Образовательная организация";
            }
        }
    },
    mounted() {
        if (this.dataProgramsMain && this.dataProgramsMain.typeProgramId) {
            this.typeProgram = this.dataProgramsMain.typeProgramId; // Установите начальное значение typeProgram
            this.getDataList();
        }
    },
    methods: {
        getDataList() {
            const cat = this.typeProgram;
            axios.get(`/api/organizations_filter/${cat}`)
                .then(response => {
                    this.dataList = response.data;
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        console.log(error.response.data.errors.name[0]);
                    } else {
                        console.log('Произошла ошибка при загрузке данных.');
                    }
                })
        },
        selectItem(item) {
            if (item.name_filter) {
                this.titleBtn = item.name_filter;
            } else if (item.name_short) {
                this.titleBtn = item.name_short;
            }
            this.$emit('org-id', item.id);
        }
    }
}
</script>
