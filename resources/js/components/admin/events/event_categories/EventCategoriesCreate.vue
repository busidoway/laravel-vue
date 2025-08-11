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
        response: {
            status: "",
            info: ""
        },
        errors: []
    }),
    methods: {
        async saveData() {
            this.loaderSend = true;
            this.response = { status: "", info: "" };
            this.errors = [];

            try {
                const response = await axios.post('/api/event_categories_store', {
                    title: this.title,
                    code: this.code
                });

                if (response.data.success) {
                    const eventCategory = response.data.data;

                    await this.addEventSubCategoryJoin(Number(eventCategory.id));

                    await this.$router.push({
                        name: 'EventCategoriesEdit',
                        params: { id: String(eventCategory.id), success: true, message: response.data.message }
                    });
                } else if (response.data.error) {
                    this.response.status = 'error';
                    this.errorResponseMessage(response.data.message);
                }
            } catch (error) {
                this.response.status = 'error';
                this.errors.push('Ошибка при сохранении данных');
            } finally {
                this.loaderSend = false;
            }
        },
        async addEventSubCategoryJoin(eventCategoryId) {
            if (this.dataSubCategories.length > 0) {
                try {
                    const payload = this.dataSubCategories.map(item => ({
                        event_category_id: eventCategoryId,
                        event_sub_category_id: item.id,
                    }));

                    const resp = await axios.post('/api/event_sub_category_join_store', payload);
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
};
</script>
