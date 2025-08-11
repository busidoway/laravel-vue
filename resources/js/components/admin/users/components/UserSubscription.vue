<template>
    <div class="card card-module border-0 shadow">
        <div class="card-body">
            <div class="py-2 px-2 mb-4 fs-5 text-success" v-if="state.subs.success">{{ state.subs.info }}</div>
            <div class="py-2 px-2 mb-4 fs-5 text-danger" v-if="state.subs.error">{{ state.subs.info }}</div>
            <div class="d-flex justify-content-center position-absolute top-50 start-50 translate-middle" v-if="loading.subs.active">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="mx-1 px-2 d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="h4 m-0">Подписки</div>
                    <div class="spinner-border text-gray-400 ms-3" role="status" v-if="loading.subs.progress">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                </div>
                <div class="">
                    <a class="text-danger" data-bs-toggle="modal" data-bs-target="#modal-delete-list" v-if="deleteBtn.active"><i class="fas fa-trash-alt me-2"></i>Удалить отмеченные</a>
                </div>
            </div>
            <div class="mx-0 row mb-4" >
                <form id="subsListForm" action="">
                    <table class="table table-centered table-nowrap mb-0 rounded table-hover" v-if="subsList.length !== 0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" id="video_check_all" name="video_check_all" class="form-check-input" @change="checkAllItems($event)" :checked="checkedAllItems" style="height:15.75px;width:15.75px;">
                                    </div>
                                </th>
                                <th class="border-0">#</th>
                                <th class="border-0">Кол-во месяцев</th>
                                <th class="border-0">Стартовая дата</th>
                                <th class="border-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(subs, index) in subsList" :key="subs.id">
                                <td class="align-middle">
                                    <input type="checkbox" :id="'subs_check_item'+ subs.id" name="subs_check_item" class="form-check-input" @click="checkItemSubs($event, subs.id)" :value="subs.id" v-model="subsCheckItems">
                                </td>
                                <td class="align-middle">
                                    {{ subs.id }}
                                </td>
                                <td class="align-middle text-wrap">
                                    <input type="number" name="subs_period" class="form-control subs_period" min="1" value="1" :data-index="index + 1" style="max-width: 150px;">
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center text-gray-300">
                                        <div>
                                            <input type="text" name="subs_date_start" @click.once="setDatepicker($event.target, subs.id)" class="date_start range_item range_item_subs" :data-id="subs.id" :data-index="index + 1" :class="{'active': subs.date_range}" :value="subs.date_start" placeholder="д. м. г.">
                                        </div>
                                        <div class="ms-2">
                                            <i class="far fa-clock"></i>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group m-0">
                                        <a class="text-danger" data-bs-toggle="modal" data-bs-target="#modal-delete" @click.prevent="getSubsData(subs.id)"><span class="fas fa-trash-alt me-2"></span>Удалить</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="mb-2 mx-0 row">
                <div class="button-group">
                    <button class="btn btn-primary text-white" @click.prevent="addSubs">Добавить подписку</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';

export default {
    props: {
        userId: Number
    },
    data: () => ({
        loading: {
            subs: {
                active: true,
                progress: false
            }
        },
        state: {
            subs: {
                success: false,
                error: false,
                info: ""
            }
        },
        subsList: [],
        subsCheckItems: [],
        checkedAllItems: false,
        deleteBtn: {
            active: false 
        }
    }),
    mounted() {
        this.getUserSubs();
    },
    methods: {
        getUserSubs() {
            let user_id = this.userId;
            if(user_id) {
                axios.get('/api/user_subscriptions/' + user_id)
                .then(res => {
                    this.loading.subs.active = false;
                    this.subsList = res.data.user_subscriptions;
                }).catch(err => {
                    this.state.subs.error = true;
                    this.state.subs.success = false;
                    this.state.subs.info = "Ошибка загрузки"
                })
            }
        },
        addSubs() {
            let index = this.subsList.length;
           
            this.subsList.push({
                id: ++index
            });
        },
        checkAllItems(event) {
        
        },
        checkItemSubs(event, id) {
        
        },
        setDatepicker(item, id) {
            let dp = new AirDatepicker(item, {
                // selectedDates: date_sel
            });
            dp.show();
        },
        getSubsData(id) {
            
        }
    }
}
</script>