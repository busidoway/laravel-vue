<template>
    <div class="cities-edit">
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
import NavPage from "../components/NavPage.vue";
import axios from "axios";
export default {
    components: {
        NavPage
    },
    data: () => ({
        navTitle: "Добавление города",
        dataNav: [
            {
                title: 'Список городов',
                path: '/admin/cities/'
            },
        ],
        name: "",
        email: "",
        description: "",
        textButtonSend: "Сохранить",
        loaderSend: false,
        response: {
            status: "",
            info: ""
        },
    }),
    created() {
        if (this.$route.params.id) {
            this.navTitle = "Редактирование города";
            this.fetchData();
        }
    },
    methods: {
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

            axios.post('/api/cities_store', {
                name: this.name
            })
                .then(response => {
                    this.response.status = 'success';
                    this.response.info = 'Данные успешно сохранены!';

                    // Перенаправление на страницу редактирования
                    this.$router.push({name: 'CitiesEdit', params: {id: response.data.id}});
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
            axios.put(`/api/cities_update/${id}`, {
                name: this.name
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
            axios.get(`/api/cities_edit/${id}`)
                .then(response => {
                    this.name = response.data.name;
                })
                .catch(error => {
                    console.error('Ошибка при загрузке данных:', error);
                    this.response.status = 'error';
                    this.response.info = 'Не удалось загрузить данные.';
                });
        },
        returnBack() {
            this.$router.push({ name: 'Cities' });
        }
    }
}
</script>
