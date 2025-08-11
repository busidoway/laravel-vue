<template>
    <div class="type-programs-edit">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page :title="navTitle" :nav-list="dataNav" />
        </div>
        <div class="card card-module border-0 shadow components-section">
            <div class="card-body">
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="organization">Образовательная организация</label>
                    <div class="input-group">
                        <select name="organization" id="organization" class="form-select" v-model="organization">
                            <option v-for="item in dataOrganizations" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="programs">Образовательная программа</label>
                    <div class="input-group">
                        <select name="programs" id="programs" class="form-select" v-model="program">
                            <option v-for="item in dataPrograms" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <div class="col-md-6">
                        <label for="form_education">Форма обучения</label>
                        <div class="input-group">
                            <select name="form_education" id="form_education" class="form-select" v-model="formEducation">
                                <option v-for="item in dataFormEducation" :value="item.id">{{ item.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="date">Дата</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="date" v-model="date">
                    </div>
                </div>
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="price">Цена, руб.</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="price" min="0" placeholder="0" v-model="price">
                    </div>
                </div>
                <div class="row mb-3 mx-0 px-3 col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input form-check-green-input" type="checkbox" name="price_exam" id="price_exam" v-model="priceExam">
                        <label class="form-check-label" for="price_exam">Включить стоимость экзамена</label>
                    </div>
                </div>
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="duration">Продолжительность</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="duration" v-model="duration">
                    </div>
                </div>
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="duration">Продление аттестата</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="extension" v-model="extension">
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
        navTitle: "Создание образовательной программы",
        dataNav: [
            {
                title: 'Образовательной программы',
                path: '/admin/programs_edu/'
            },
        ],
        dataOrganizations: [],
        dataPrograms: [],
        dataFormEducation: [],
        organization: null,
        typeProgram: null,
        program: null,
        // formEducation: [],
        formEducation: null,
        date: "",
        price: "",
        priceExam: false,
        duration: "",
        extension: "",
        textButtonSend: "Сохранить",
        loaderSend: false,
        response: {
            status: "",
            info: ""
        },
    }),
    computed: {
        formEduSelected() {
            return this.formEducation;
        }
    },
    created() {
        if (this.$route.params.id) {
            this.navTitle = "Редактирование образовательной программы";
            this.fetchData();
        }
        this.getOrganizations();
        this.getPrograms();
        this.getDataFormEducation();
    },
    methods: {
        getDataFormEducation() {
            axios.get('/api/form_education')
                .then(response => {
                    this.dataFormEducation = response.data;
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getOrganizations() {
            axios.get('/api/organizations')
                .then(response => {
                    this.dataOrganizations = response.data;
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getPrograms() {
            axios.get('/api/programs')
                .then(response => {
                    this.dataPrograms = response.data;
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

            let priceExam = "";
            this.priceExam ? priceExam = 1 : priceExam = "";

            axios.post('/api/programs_edu_store', {
                organization: this.organization,
                program: this.program,
                date: this.date,
                price: this.price,
                form_education: this.formEducation,
                duration: this.duration,
                extension: this.extension,
                price_exam: priceExam
            })
                .then(response => {
                    this.response.status = 'success';
                    this.response.info = 'Данные успешно сохранены!';

                    console.log(response.data)

                    // Перенаправление на страницу редактирования
                    this.$router.push({name: 'ProgramsEduEdit', params: {id: response.data.programs_edu.id}});
                })
                .catch(error => {
                    console.log(error.response)
                    this.response.status = 'error';
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.response.info = error.response.data.errors.price[0];
                        this.response.info = error.response.data.errors.date[0];
                        this.response.info = error.response.data.errors.program[0];
                        this.response.info = error.response.data.errors.type_program[0];
                        this.response.info = error.response.data.errors.organization[0];
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
            let priceExam = "";
            this.priceExam ? priceExam = 1 : priceExam = "";
            axios.put(`/api/programs_edu_update/${id}`, {
                organization: this.organization,
                program: this.program,
                date: this.date,
                price: this.price,
                form_education: this.formEducation,
                duration: this.duration,
                extension: this.extension,
                price_exam: priceExam
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
            axios.get(`/api/programs_edu_edit/${id}`)
                .then(response => {
                    this.organization = response.data.organization_id;
                    this.program = response.data.program_id;
                    this.date = response.data.date;
                    this.price = response.data.price;
                    this.formEducation = response.data.form_education_id;
                    this.duration = response.data.duration;
                    this.extension = response.data.extension;
                    this.priceExam = response.data.price_exam !== '' && response.data.price_exam !== null && response.data.price_exam !== 'null' ? response.data.price_exam : this.priceExam;
                })
                .catch(error => {
                    console.error('Ошибка при загрузке данных:', error);
                    this.response.status = 'error';
                    this.response.info = 'Не удалось загрузить данные.';
                });
        },
        returnBack() {
            this.$router.push({ name: 'ProgramsEdu' });
        }
    }
}
</script>
