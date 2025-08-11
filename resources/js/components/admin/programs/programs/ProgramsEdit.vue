<template>
    <div class="type-programs-edit">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page :title="navTitle" :nav-list="dataNav" />
        </div>
        <div class="card card-module border-0 shadow components-section">
            <div class="card-body">
                <div class="row mb-4 mx-0">
                    <label for="header">Наименование</label>
                    <div class="input-group">
                        <input name="text" id="name" class="form-control" placeholder="Наименование" v-model="name">
                    </div>
                </div>
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="type_program">Группа</label>
                    <div class="input-group">
                        <select name="type_program" id="type_program" class="form-select" v-model="programGroup">
                            <option v-for="item in dataProgramsGroup" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="type_program">Тип программы</label>
                    <div class="input-group">
                        <select name="type_program" id="type_program" class="form-select" v-model="typeProgram">
                            <option v-for="item in dataTypePrograms" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="description">Описание</label>
                    <div class="">
                        <textarea name="description" id="description" class="form-control" rows="10" placeholder="Описание" v-model="description"></textarea>
                    </div>
                </div>
                <div class="mx-0 mb-3 row">
                    <div class="button-group d-flex align-items-center">
                        <button @click.prevent="returnBack" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i>Назад</button>
                        <button class="btn btn-success text-white ms-3" @click.prevent="saveData">{{ textButtonSend }}</button>
                        <div class="spinner-border text-gray-400 ms-3" role="status" v-if="loaderSend">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div :class="[(response.status == 'success') ? 'text-success' : (response.status == 'error') ? 'text-danger' : '', 'response-info ms-4']" v-if="response.info">
                            {{ response.info }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import NavPage from "../../components/NavPage.vue";
import axios from "axios";
export default {
    components: {
        NavPage
    },
    data: () => ({
        navTitle: "Создание программы",
        dataNav: [
            {
                title: 'Программы',
                path: '/admin/programs/'
            },
        ],
        name: "",
        description: "",
        dataTypePrograms: [],
        typeProgram: null,
        dataProgramsGroup: [],
        programGroup: null,
        textButtonSend: "Сохранить",
        loaderSend: false,
        response: {
            status: "",
            info: ""
        },
    }),
    created() {
        if (this.$route.params.id) {
            this.navTitle = "Редактирование программы";
            this.fetchData();
        }
        this.getTypePrograms();
        this.getProgramsGroup();
    },
    methods: {
        getTypePrograms() {
            axios.get('/api/type_programs')
                .then(response => {
                    this.dataTypePrograms = response.data;
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getProgramsGroup() {
            axios.get('/api/programs_group')
                .then(response => {
                    this.dataProgramsGroup = response.data;
                })
                .catch(error => {
                    console.log(error)
                })
        },
        saveData() {
            this.loaderSend = true;
            this.response = { status: "", info: "" };

            if (this.$route.params.id) {
                this.updateData();
            } else {
                this.addNewData();
            }
        },
        addNewData() {
            this.loaderSend = true;
            this.response = { status: "", info: "" };

            axios.post('/api/programs_store', {
                name: this.name,
                description: this.description,
                type_program: this.typeProgram,
                programs_group: this.programGroup,
            })
                .then(response => {
                    this.response.status = 'success';
                    this.response.info = 'Данные успешно сохранены!';

                    // Перенаправление на страницу редактирования
                    this.$router.push({name: 'ProgramsEdit', params: {id: response.data.id}});
                })
                .catch(error => {
                    this.response.status = 'error';
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.response.info = error.response.data.errors.name[0];
                    } else {
                        this.response.info = 'Произошла ошибка при сохранении данных.';
                    }
                })
                .finally(() => {
                    this.loaderSend = false;
                });
        },
        updateData() {
            const id = this.$route.params.id;
            axios.put(`/api/programs_update/${id}`, {
                name: this.name,
                description: this.description,
                type_program: this.typeProgram,
                programs_group: this.programGroup,
            })
                .then(response => {
                    this.response.status = 'success';
                    this.response.info = 'Данные успешно обновлены!';
                })
                .catch(error => {
                    this.response.status = 'error';
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.response.info = error.response.data.errors.name[0];
                    } else {
                        this.response.info = 'Произошла ошибка при обновлении данных.';
                    }
                })
                .finally(() => {
                    this.loaderSend = false;
                });
        },
        fetchData() {
            const id = this.$route.params.id;
            axios.get(`/api/programs_edit/${id}`)
                .then(response => {
                    if(response.data.program) {
                        this.name = response.data.program.name;
                        this.description = response.data.program.description;
                    }
                    if(response.data.program_join) {
                        this.typeProgram = response.data.program_join.type_program_id;
                    }
                    if(response.data.programs_groups_join) {
                        this.programGroup = response.data.programs_groups_join.programs_group_id;
                    }
                })
                .catch(error => {
                    console.error('Ошибка при загрузке данных:', error);
                    this.response.status = 'error';
                    this.response.info = 'Не удалось загрузить данные.';
                });
        },
        returnBack() {
            this.$router.push({ name: 'Programs' });
        }
    }
}
</script>
