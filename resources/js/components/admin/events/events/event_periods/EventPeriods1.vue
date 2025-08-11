<template>
    <div class="event-periods">
        <div class="event-periods__content">
            <div class="event-periods__periods">
                <table class="table event-periods__table table-centered table-nowrap mb-0 rounded table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0 table-th__check">
                            <div class="d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    id="video_check_all"
                                    name="video_check_all"
                                    class="form-check-input"
                                    @change="checkAllItems($event)"
                                    :checked="checkedAllItems"
                                    style="height:15.75px;width:15.75px;"
                                >
                            </div>
                        </th>
                        <th class="border-0 table-th__index">#</th>
                        <th class="border-0 table-th__period">Период</th>
                        <th class="border-0 table-th__price">Цена</th>
                        <th class="border-0 table-th__opt">
                            <button
                                type="button"
                                class="btn btn-period-remove btn-period-remove-checked text-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-delete-list"
                                v-if="deleteBtn.active"
                            >
                                <span class="fas fa-trash-alt"></span>
                                <span class="ms-1">Удалить отмеченные</span>
                            </button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in dataItems" :key="item.dateStart">
                            <td class="align-middle">
                                <input
                                    type="checkbox"
                                    :id="'period_check_item_'+ item.index"
                                    name="period_check_item"
                                    class="form-check-input"
                                    @click="checkItem($event, item.index)"
                                    :value="item.index"
                                    v-model="checkItems"
                                >
                            </td>
                            <td class="align-middle">
                                <span>{{ item.index }}</span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center text-gray-500">
                                    <div v-if="item.dateStart" class="d-sm-flex align-items-center">
                                        <input
                                            type="text"
                                            name="date_start"
                                            @click.once="setDatepicker($event.target, item.id)"
                                            class="date_start range_item_event form-control"
                                            :data-id="item.id"
                                            :class="{ 'active': item.dateRange }"
                                            v-model="item.dateStart"
                                            placeholder="д. м. г."
                                        >
                                        <span class="mx-2">—</span>
                                        <input
                                            type="text"
                                            name="date_end"
                                            @click.once="setDatepicker($event.target, item.id)"
                                            :data-id="item.id"
                                            :class="[
                                                'date_end',
                                                'range_item_event',
                                                'form-control',
                                                // errorInp.id == item.id ? 'error-inp' : ''
                                            ]"
                                            v-model="item.dateEnd"
                                            placeholder="д. м. г."
                                        >
                                    </div>
                                    <div v-else class="d-sm-flex align-items-center">
                                        <input
                                            type="text"
                                            name="date_start"
                                            @click.once="setDatepicker($event.target, item.id)"
                                            class="date_start range_item_event form-control"
                                            :data-id="item.id"
                                            :class="{ 'active': item.dateRange }"
                                            placeholder="д. м. г."
                                            v-model="item.dateEnd"
                                        >
                                        <span class="mx-2">—</span>
                                        <input
                                            type="text"
                                            name="date_end"
                                            @click.once="setDatepicker($event.target, item.id)"
                                            :data-id="item.id"
                                            :class="[
                                                'date_end',
                                                'range_item_event',
                                                'form-control',
                                                // errorInp.id == item.id ? 'error-inp' : ''
                                            ]"
                                            placeholder="д. м. г."
                                            v-model="item.dateEnd"
                                        >
                                    </div>
<!--                                    <div class="ms-2">-->
<!--                                        <i class="far fa-clock"></i>-->
<!--                                    </div>-->
                                </div>
                            </td>
                            <td class="align-middle">
                                <input name="event_period_price" type="text" class="form-control event_period_price" v-model="item.price">
                            </td>
                            <td class="align-middle">
                                <button
                                    type="button"
                                    class="btn btn-period-remove text-danger"
                                    data-bs-toggle="modal"
                                    :data-bs-target="'#modal-delete-period-' + itemInfo.index"
                                    @click.prevent="getItemData(item.index)"
                                    title="Удалить"
                                >
                                    <span class="fas fa-trash-alt"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="event-periods__buttons mt-2 mb-2 mx-0 row">
                <div class="button-group">
                    <button class="btn btn-primary text-white" @click.prevent="addNewItem">Добавить период</button>
                </div>
            </div>
        </div>
        <modal-delete
            @delete-confirm="deleteItem"
            :modal-id="'modal-delete-period-' + itemInfo.index"
            :id="itemInfo.index"
            :title="'запись № ' + itemInfo.index"
        />
        <modal-delete-list
            @delete-confirm="deleteSelectedItems"
            :title="'Удалить отмеченные?'"
        />
    </div>
</template>

<script>
import axios from "axios";
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';
import ModalDelete from '../../../components/ModalDelete.vue';
import ModalDeleteList from '../../../components/ModalDeleteList.vue';
export default {
    components: {
        AirDatepicker,
        ModalDelete,
        ModalDeleteList
    },
    props: {
        eventId: Number
    },
    data: () => ({
        dataItems: [],
        checkItems: [],
        checkedAllItems: false,
        itemInfo: {
            index: null,
            id: null,
            title: null
        },
        deleteBtn: {
            active: false
        }
    }),
    methods: {
        addNewItem() {
            const countDataItems = this.dataItems.length;

            this.dataItems.push({
                index: countDataItems + 1,
                id: null,
                dateStart: null,
                dateEnd: null,
                price: null
            });

            this.iterationDataArray(this.dataItems, 'index');
        },
        checkAllItems($event) {

        },
        checkItem(event, itemIdx) {
            if (event.target.checked) {
                this.checkItems.push(itemIdx);
            } else {
                this.checkItems.splice(this.checkItems.indexOf(itemIdx), 1);
            }

            if (this.checkItems.length !== 0) {
                this.deleteBtn.active = true;
            } else {
                this.deleteBtn.active = false;
            }
            // console.log(this.checkItems)
        },
        setDatepicker(item, id){

            const eventId = Number(this.eventId);
            const itemId = Number(id);

            if (itemId) {
                axios.get('/api/event_period_date/' + itemId + '/' + eventId)
                    .then(res => {
                        // console.log(res);
                        if (res.status) {
                            let itemName = item.getAttribute('name');
                            let dateStart = res.data.data.date_start;
                            let dateEnd = res.data.data.date_end;
                            let dateSel;

                            if(itemName === 'date_start'){
                                dateSel = dateStart;
                            }else if(itemName === 'date_end'){
                                dateSel = dateEnd;
                            }

                            let dp = new AirDatepicker(item, {
                                range: false,
                                buttons: ['clear'],
                                selectedDates: dateSel
                            });
                            dp.show();
                        }
                    })
            } else {
                let dp = new AirDatepicker(item, {
                    range: false,
                    buttons: ['clear']
                });
                dp.show();
            }

        },
        getItemData(index) {
            this.itemInfo.index = index ? index : null;
        },
        deleteItem(deleteIndex) {
            console.log('deleteIndex:', deleteIndex);
            const idx = this.dataItems.findIndex(item => item.index === deleteIndex);
            if (idx !== -1) {
                console.log('idx:', idx);
                console.log('dataItems:', this.dataItems);
                this.dataItems.splice(idx, 1);
                this.iterationDataArray(this.dataItems, 'index');
            }
        },
        deleteSelectedItems() {
            if (this.checkItems.length !== 0) {

                for (let i = 0; i < this.checkItems.length; i++) {
                    const delIdx = Number(this.checkItems[i]);
                    const idx = this.dataItems.findIndex(item => item.index === delIdx);
                    if (idx !== -1) {
                        this.dataItems.splice(idx, 1);
                    }
                }

                console.log(this.dataItems);

                this.checkItems = [];
                this.iterationDataArray(this.dataItems, 'index');
                this.deleteBtn.active = false;
            }
        },
        iterationDataArray(arr, key) {
            if (arr.length > 0) {
                for (let i = 0; i < arr.length; i++) {
                    arr[i][key] = i + 1;
                }
            }
        }
    }
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
