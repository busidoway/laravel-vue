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
                                    @change="toggleAll()"
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
                                    @click="onCheckItem($event, item.index)"
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

<script lang="ts">
import { ref, defineComponent, PropType, toRefs, reactive } from  '@vue/composition-api';
import axios from "axios";
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';

import ModalDelete from '../../../components/ModalDelete.vue';
import ModalDeleteList from '../../../components/ModalDeleteList.vue';

interface DataItem {
    index: number;
    id: number | null;
    dateStart: Date | null;
    dateEnd: Date | null;
    price: number | null;
}

interface ItemInfo {
    index?: number | null;
    id?: number | null;
    title?: string | null;
}

interface DeleteBtn {
    active: boolean;
}

type DataItemKey = keyof DataItem;

export default defineComponent( {
    components: {
        ModalDelete,
        ModalDeleteList
    },
    props: {
        eventId: {
            type: Number
        }
    },
    setup(props) {
        const dataItems = ref<DataItem[]>([]);
        const checkItems = ref<number[]>([]);
        const checkedAllItems = ref<boolean>(false);
        const itemInfo = ref<ItemInfo>({});
        const deleteBtn = ref<DeleteBtn>({ active: false });
        const { eventId } = toRefs(props);

        const onCheckItem = (event: Event, idx: number) => checkItem(event, idx, checkItems, deleteBtn);


        function addNewItem(): void {
            const countDataItems: number = dataItems.value.length;

            dataItems.value.push({
                index: countDataItems + 1,
                id: null,
                dateStart: null,
                dateEnd: null,
                price: null
            });

            iterationDataArray(dataItems.value, 'index');
        }

        function checkItem(event: Event, itemIdx: number, checkItems: Ref<number[]>, deleteBtn: Ref<{ active: boolean }>): void {
            const target = event.target as HTMLInputElement;

            if (target.checked) {
                if (!checkItems.value.includes(itemIdx)) {
                    checkItems.value.push(itemIdx);
                }
            } else {
                const pos = checkItems.value.indexOf(itemIdx);
                if (pos !== -1) {
                    checkItems.value.splice(pos, 1);
                }
            }

            deleteBtn.value.active = checkItems.value.length > 0;
        }

        function getItemData(index: number): void {
            itemInfo.value.index = index ? index : null;
        }

        function toggleAll(): void {
            if (checkedAllItems.value) {
                checkItems.value = dataItems.value.map(item => item.index);
            } else {
                checkItems.value = [];
            }

            deleteBtn.value.active = checkItems.value.length > 0;
        }

        function deleteItem(itemIndex: number): void {
            const idx = dataItems.value.findIndex(item => item.index === itemIndex);

            if (idx !== -1) {
                dataItems.value.splice(idx, 1);
                iterationDataArray(dataItems.value, 'index');
            }
        }

        function deleteSelectedItems():void {
            if (checkItems.value.length !== 0) return;

            // for (let i = 0; i < checkItems.value.length; i++) {
            //     const delIdx: number = Number(checkItems.value[i]);
            //     const idx = dataItems.value.findIndex(item => item.index === delIdx);
            //     if (idx !== -1) {
            //         dataItems.value.splice(idx, 1);
            //     }
            // }

            dataItems.value = dataItems.value.filter(
                item => !checkItems.value.includes(item.index)
            );

            iterationDataArray(dataItems.value, 'index');
            checkItems.value = [];
            deleteBtn.value.active = false;
        }

        function iterationDataArray<K extends DataItemKey>(arr: DataItem[], key: K): void {
            arr.forEach((item, idx) => {
                item[key] = (idx + 1) as any;
            });
        }

        function setDatepicker(): void {
            // const eventId = Number(eventId);
        }

        return {
            dataItems,
            checkedAllItems,
            deleteBtn,
            addNewItem,
            iterationDataArray,
            checkItem,
            onCheckItem,
            getItemData,
            toggleAll,
            deleteItem,
            deleteSelectedItems
        }
    }
});
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
