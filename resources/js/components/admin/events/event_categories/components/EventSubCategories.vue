<template>
    <div class="list-items">
        <div class="list-items__header fw-bolder">Подкатегории</div>
        <div class="list-items__content">
            <div class="list-item" v-for="(item, index) in dataSelected">
                <span class="list-item__title">
                    {{ item.title }}
                </span>
                <span class="list-item__delete text-danger" title="Удалить" @click.prevent="deleteDataItem(index, item.id)">
                    <i class="fas fa-trash-alt me-1"></i>
                </span>
            </div>
        </div>
        <div class="buttons__content">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEventSubCategories">Выбрать</button>
        </div>
        <event-sub-categories-modal :data-items="dataItems" @data-selected="getDataSelected" />
    </div>
</template>

<script>
import axios from "axios";
import EventSubCategoriesModal from "./EventSubCategoriesModal.vue";
export default {
    name: 'EventSubCategories',
    components: {
        EventSubCategoriesModal,
    },
    props: {
        dataSubCategories: {
            type: Array,
            default: () => []
        }
    },
    emits: [
        'data-selected',
    ],
    data: () => ({
        dataIds: [],
        dataItems: [],
        dataSelected: [],
        dataJoinIds: []
    }),
    mounted() {
        this.getData()
    },
    watch: {
        dataSubCategories: {
            immediate: true,
            handler(newVal) {
                this.dataJoinIds = [...newVal];
                this.getData();
            }
        }
    },
    methods: {
        getData () {
            let dataIdsAll = [];

            if (this.dataJoinIds && this.dataJoinIds.length > 0) {
                dataIdsAll.push(...this.dataJoinIds);
            }

            if (this.dataIds && this.dataIds.length > 0) {
                dataIdsAll.push(...this.dataIds);
            }

            axios.post('/api/data_event_sub_categories', {
                ids: dataIdsAll
            })
            .then((response) => {
                this.dataItems = response.data.data.sub_categories;
                this.dataSelected = response.data.data.sub_categories_selected;
            }).catch(error => {
                console.log(error);
            })
        },
        getDataSelected(data) {
            if (data && data.length > 0) {
                this.dataIds = data.map(item => { return item.id; });
                this.dataSelected = data;
                this.$emit('data-selected', data);
                this.getData();
            }
        },
        deleteDataItem(index, id) {
            const indexItem = this.dataIds.indexOf(id);
            const indexItemJoin = this.dataJoinIds.indexOf(id);
            if (index > -1) {
                this.dataSelected.splice(index, 1);
            }
            if (indexItem > -1) {
                this.dataIds.splice(indexItem, 1);
            }
            if (indexItemJoin > -1) {
                this.dataJoinIds.splice(indexItemJoin, 1);
            }
            this.$emit('data-selected', this.dataSelected);
            this.getData();
        }
    }
}
</script>
