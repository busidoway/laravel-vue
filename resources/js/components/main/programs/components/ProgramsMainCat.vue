<template>
    <div class="programs-main-cat">
        <div class="programs-main-info">
            <div class="programs-info-item" v-for="item in dataList" v-if="item.url && item.id === activeId">
                <div class="programs-info-text fs-5" v-if="item.text">
                    {{ item.text }}
                </div>
                <div class="programs-info-btn">
                    <a :href="item.url" class="btn btn-primary">
<!--                        <i class="fa-solid fa-angle-right"></i>-->
                        Подробнее
                    </a>
                </div>
            </div>
        </div>
        <div class="programs-main-cat__content">
            <ul class="nav nav-pills">
                <li class="nav-item" v-for="item in dataList">
                    <a :class="[activeId === item.id ? 'active' : '','nav-link']" aria-current="page" href="#" @click.prevent="selectItem(item)">{{ item.name }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: 'ProgramsMainCat',
    data: () => ({
        dataList: [],
        activeId: null,
        pagePath: '/programs/'
    }),
    created() {
        this.getDataList();
    },
    methods: {
        getDataList() {
            axios.get('/api/type_programs')
                .then(response => {
                    this.dataList = response.data;

                    // Получить code из параметров маршрута
                    const code = this.$route.params.code;
                    // Получить activeId из localStorage, если он там есть
                    const savedActiveId = localStorage.getItem('activeId');

                    if (code) {
                        // Найти элемент с соответствующим значением code
                        const foundItem = response.data.find(item => item.code === code);
                        if (foundItem) {
                            this.activeId = foundItem.id;
                            localStorage.setItem('activeId', this.activeId); // Сохранить в localStorage
                            this.$emit('type-program-id', this.activeId);
                            this.$emit('type-program-code', code);
                        } else {
                            this.setDefaultActiveId(response.data);
                        }
                    } else if (savedActiveId) {
                        // Если code отсутствует в URL, но есть сохраненный activeId в localStorage
                        const foundItem = response.data.find(item => item.id === parseInt(savedActiveId));
                        if (foundItem) {
                            this.activeId = foundItem.id;
                            this.$emit('type-program-id', this.activeId);
                            this.$emit('type-program-code', foundItem.code);
                        } else {
                            this.setDefaultActiveId(response.data);
                        }
                    } else {
                        // Если нет ни code в URL, ни сохраненного activeId, установите значения по умолчанию
                        this.setDefaultActiveId(response.data);
                    }
                })
                .catch(error => {
                    console.log('Произошла ошибка при загрузке данных.', error);
                });
        },
        setDefaultActiveId(data) {
            this.activeId = data[0].id;
            localStorage.setItem('activeId', this.activeId);
            this.$emit('type-program-id', this.activeId);
            this.$emit('type-program-code', data[0].code);
        },
        selectItem(item) {
            this.activeId = item.id;
            localStorage.setItem('activeId', item.id);
            this.$emit('type-program-id', item.id);
            this.$emit('type-program-code', item.code);
            this.$router.push(this.pagePath + item.code + '/1');
        }
    }
}
</script>
