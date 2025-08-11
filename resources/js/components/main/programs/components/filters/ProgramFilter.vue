<template>
    <div class="program-filter programs-main-filters__item">
        <div class="dropdown">
            <button class="btn btn-filter dropdown-toggle" type="button" id="program_filter" data-bs-toggle="dropdown" aria-expanded="false">
                <span>{{ titleBtn }}</span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="program_filter">
                <li v-for="item in dataGroupsList" :key="item.id" v-if="item.name">
                    <a class="dropdown-item" href="#" @click.prevent="selectGroupItem(item)">{{ item.name }}</a>
                </li>
                <li v-for="item in dataList" :key="item.id" v-if="item.name">
                    <a class="dropdown-item" href="#" @click.prevent="selectItem(item)">{{ item.name }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'ProgramFilter',
    props: {
        clear: Boolean
    },
    data: () => ({
        dataList: [],
        dataGroupsList: [],
        titleBtn: "Образовательная программа",
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
                this.titleBtn = "Образовательная программа";
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
            axios.get(`/api/programs_filter/${cat}`)
                .then(response => {
                    this.dataGroupsList = response.data.groups;
                    this.dataList = response.data.programs;
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
            this.titleBtn = item.name;
            this.$emit('program-id', item.id);
        },
        selectGroupItem(item) {
            this.titleBtn = item.name;
            this.$emit('program-group-id', item.id);
        }
    }
}
</script>
