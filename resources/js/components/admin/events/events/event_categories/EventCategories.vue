<template>
    <div class="event_category row mb-4 mx-0">
        <div class="col-xl-6">
            <div>
                <label for="event_category">Категория</label>
                <div class="input-group">
                    <select class="form-select" name="event_category" id="event_category" @change="getEventSubCat($event.target.value)" aria-label="select">
                        <option :value="0">-- Выбрать категорию --</option>
                        <option v-for="item in dataEventCat" :key="item.id" :value="item.id" :selected="item.id === eventCategoryId">{{ item.title }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div v-if="dataEventSubCat.length > 0">
                <label for="event_category">Доп. категория (для слайдера)</label>
                <div class="input-group">
                    <select class="form-select" name="event_sub_category" id="event_sub_category" aria-label="select">
                        <option :value="0">-- Выбрать доп. категорию --</option>
                        <option v-for="item in dataEventSubCat" :value="item.id" :selected="item.id === eventSubCategoryId">{{ item.title }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    props: {
        eventCategoryId: Number,
        eventSubCategoryId: Number,
    },
    data: () => ({
        dataEventCat: [],
        dataEventSubCat: []
    }),
    mounted() {
        this.getEventCategories();
    },
    methods: {
        async getEventCategories() {
            try {
                const resp = await axios.get('/api/event_categories');
                this.dataEventCat = resp.data;

                if (this.eventCategoryId) {
                    await this.getEventSubCat(this.eventCategoryId)
                }
            } catch (error) {
                console.log('Ошибка при загрузке категорий')
            }
        },
        async getEventSubCat(category_id) {
            if (category_id === 0) {
                this.dataEventSubCat = "";
            } else {
                try {
                    const resp = await axios.get(`/api/data_event_sub_categories/${category_id}`);
                    this.dataEventSubCat = resp.data.data;
                } catch (error) {
                    console.log('Ошибка при загрузке подкатегорий')
                }
            }
        }
    }
}
</script>
