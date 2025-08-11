<template>
    <div class="event-categories__edit">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page :title="navTitle" :nav-list="dataNav" />
        </div>
        <div class="card card-module border-0 shadow components-section">
            <div class="card-body">
                <div class="row mb-4 mx-0">
                    <label for="title">Заголовок *</label>
                    <div class="input-group">
                        <input name="title" id="title" class="form-control" placeholder="Заголовок" v-model="title">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="code">Код</label>
                    <div class="input-group">
                        <input name="code" id="code" class="form-control" placeholder="Код" v-model="code">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <event-sub-categories @data-selected="getSubCategories" :data-sub-categories="dataSubCatJoinedIds"  />
                </div>
                <div class="row mb-4 mx-0 ps-3" style="font-size:14px;">
                    * Обязательно для заполнения
                </div>
                <div class="mx-0 mb-3 row">
                    <div class="button-group d-flex align-items-center">
                        <button @click.prevent="returnBack" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i>Назад</button>
                        <button class="btn btn-success text-white ms-3" @click.prevent="saveData">{{ textButtonSend }}</button>
                        <div class="spinner-border text-gray-400 ms-3" role="status" v-if="loaderSend">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div :class="[(response.status === 'success') ? 'text-success' : (response.status === 'error') ? 'text-danger' : '', 'response-info ms-4']" v-if="response.status">
                            <span v-if="response.status === 'success'">{{ response.info }}</span>
                            <span v-else-if="response.status === 'error'">Ошибки:</span>
                            <a class="ms-1 errors-count" v-if="errors.length !== 0" href="#errors_section">{{ errors.length }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body border-0 shadow mt-3 mb-5" v-if="errors.length !== 0">
            <div class="errors-section" id="errors_section">
                <div class="message-error">
                    <div class="message-error__item" v-for="(error, index) in errors"><span>{{ index }}:</span><span class="ms-1">{{ error }}</span></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import NavPage from "../../components/NavPage.vue";
import EventSubCategories from "./components/EventSubCategories.vue";
import EventCategoryMixin from "./mixins/EventCategoryMixin";
import axios from "axios";

export default {
    components: {
        NavPage,
        EventSubCategories
    },
    mixins: [
        EventCategoryMixin
    ],
    data: () => ({
        navTitle: "Создание категории мероприятий",
        dataNav: [
            {
                title: 'Категории мероприятий',
                path: '/admin/event_categories/'
            },
        ],
        title: "",
        code: "",
        textButtonSend: "Сохранить",
        loaderSend: false,
        dataSubCategories: [],
        dataSubCatJoined: [],
        dataSubCatJoinedIds: [],
        response: {
            status: "",
            info: ""
        },
        errors: []
    }),
    created() {
        if (this.$route.params.id) {
            this.navTitle = "Редактирование категории мероприятий";
            this.fetchData();
        }
        console.log(this.$route.params)
        if (this.$route.params.success) {
            this.response.status = 'success';
            this.response.info = this.$route.params.message;
        }
    },
    methods: {
        saveData() {
            this.loaderSend = true;
            this.response = { status: "", info: "" };

            if (this.$route.params.id) {
                this.updateData();
            }
        },
        async updateData() {
            const id = this.$route.params.id;

            try {
                const response = await axios.put(`/api/event_categories_update/${id}`, { title: this.title, code: this.code });

                if (response.data.success) {
                    // const eventCategories = response.data.data;

                    // ВАЖНО: Сначала привязываем подкатегории
                    await this.updateEventSubCategoryJoin(Number(id));

                    this.response.status = 'success';
                    this.response.info = response.data.message;
                    this.fetchData();
                } else if (response.data.error) {
                    this.response.status = 'error';
                    this.errorResponseMessage(response.data.message);
                }
            } catch (error) {
                const message = 'Произошла ошибка при сохранении данных';
                console.log(error)
                this.errorMessage(error, message);
            } finally {
                this.loaderSend = false;
            }
        },
        fetchData() {
            const id = this.$route.params.id;
            this.errors = [];
            axios.get(`/api/event_categories_edit/${id}`)
                .then(response => {
                    this.title = response.data.title;
                    this.code = response.data.code;
                    this.getSubCategoriesJoined(Number(id));
                })
                .catch(error => {
                    const message = 'Ошибка при загрузке данных';
                    this.errorMessage(error, message);
                });
        },
        getSubCategories(data) {
            if (data) this.dataSubCategories = data
        },
        getSubCategoriesJoined(eventCategoryId) {
            if (eventCategoryId) {
                axios.get(`/api/event_sub_category_join_edit/${eventCategoryId}`)
                    .then(resp => {

                        if (resp.data.success) {
                            // this.dataSubCatJoined = resp.data.data;
                            this.dataSubCatJoinedIds = resp.data.data.map(item => { return item.event_sub_category_id });
                            console.log('dataSubCatJoined:', this.dataSubCatJoined)
                        }

                    })
                    .catch(error => {
                        const message = 'Произошла ошибка при загрузке подкатегорий';
                        this.errorMessage(error, message);
                    });
            }
        },
        async updateEventSubCategoryJoin(eventCategoryId) {
            if (this.dataSubCategories.length > 0) {
                try {
                    const payload = this.dataSubCategories.map(item => ({
                        // event_category_id: eventCategoryId,
                        event_sub_category_id: item.id,
                    }));

                    const resp = await axios.put(`/api/event_sub_category_join_update/${eventCategoryId}`, payload);
                    if (resp.data.success) {
                        console.log('Связка создана:', resp.data.data);
                    }
                } catch (error) {
                    const message = 'Произошла ошибка при сохранении связки подкатегорий';
                    this.errorMessage(error, message);
                }
            }
        },
    }
}
</script>
