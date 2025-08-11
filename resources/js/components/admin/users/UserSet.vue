<template>
    <div class="user-set">

        <div class="card card-module border-0 shadow components-section mb-3">
            <div class="card-body">
                <div class="py-2 px-2 mb-4 fs-5 text-success" v-if="state.event.success">{{ state.event.info }}</div>
                <div class="py-2 px-2 mb-4 fs-5 text-danger" v-if="state.event.error">{{ state.event.info }}</div>
                <div class="d-flex justify-content-center position-absolute top-50 start-50 translate-middle" v-if="loading.event.active">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="mx-1 px-2 d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        <div class="h4 m-0">Мероприятия</div>
                        <div class="spinner-border text-gray-400 ms-3" role="status" v-if="loading.event.progress">
                            <span class="visually-hidden">Загрузка...</span>
                        </div>
                    </div>
                    <div class="">
                        <a class="text-danger" data-bs-toggle="modal" data-bs-target="#modal-delete-list" v-if="deleteBtn.active"><i class="fas fa-trash-alt me-2"></i>Удалить отмеченные</a>
                    </div>
                </div>
                <div class="mx-0 row mb-4" v-if="eventsList.length != 0">
                    <form id="eventsListForm" action="">
                    <table class="table table-centered table-nowrap mb-0 rounded table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">
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
                                <th class="border-0">#</th>
                                <th class="border-0">Заголовок</th>
                                <th class="border-0">Этапы</th>
                                <th class="border-0">Период</th>
                                <th class="border-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="v in eventsList" :key="v.id">
                                <td class="align-middle">
                                    <input
                                        type="checkbox"
                                        :id="'event_check_item'+ v.id"
                                        name="event_check_item"
                                        class="form-check-input"
                                        @click="checkItemEvent($event, v.id)"
                                        :value="v.id"
                                        v-model="eventCheckItems"
                                    >
                                </td>
                                <td class="align-middle">
                                    <span class="event-id" :data-id="v.id">{{ v.id }}</span>
                                </td>
                                <td class="align-middle text-wrap">
                                        <span>{{ v.title }}</span>
                                        <a class="ms-1 px-1" :href="'/admin/events/'+ v.id +'/edit'" title="Редактирование мероприятия">
                                            <span class="fas fa-edit"></span>
                                        </a>
                                </td>
                                <td class="align-middle">
                                    0
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#">Настроить</button>
                                </td>
<!--                                <td class="align-middle">-->
<!--                                    <div class="d-flex align-items-center text-gray-300">-->
<!--                                        <div v-if="v.date_start">-->
<!--                                            <input type="text" name="date_start" @click.once="setDatepicker($event.target, v.id)" class="date_start range_item range_item_event" :data-id="v.id" :class="{'active': v.date_range}" :value="v.date_start" placeholder="д. м. г.">-->
<!--                                            <span>—</span>-->
<!--                                            <input type="text" name="date_end" @click.once="setDatepicker($event.target, v.id)" :data-id="v.id" :class="['date_end', 'range_item', 'range_item_event', errorInp.id == v.id ? 'error-inp' : '']" :value="v.date_end" placeholder="д. м. г.">-->
<!--                                        </div>-->
<!--                                        <div v-else>-->
<!--                                            <input type="text" name="date_start" @click.once="setDatepicker($event.target, v.id)" class="date_start range_item range_item_event" :data-id="v.id" :class="{'active': v.date_range}" placeholder="д. м. г.">-->
<!--                                            <span>—</span>-->
<!--                                            <input type="text" name="date_end" @click.once="setDatepicker($event.target, v.id)" :data-id="v.id" :class="['date_end', 'range_item', 'range_item_event', errorInp.id == v.id ? 'error-inp' : '']" placeholder="д. м. г.">-->
<!--                                        </div>-->
<!--                                        <div class="ms-2">-->
<!--                                            <i class="far fa-clock"></i>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </td>-->
                                <td class="align-middle">
                                    <div class="btn-group m-0">
                                        <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="icon icon-sm">
                                                <span class="fas fa-ellipsis-h icon-dark"></span>
                                            </span>
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu py-0">
                                            <a class="dropdown-item" :href="'/admin/events/'+ v.id +'/edit'"><span class="fas fa-edit me-2"></span>Перейти в редактирование</a>
                                            <button
                                                type="button"
                                                class="dropdown-item text-danger rounded-bottom"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-delete"
                                                @click.prevent="getEventData(v.id, v.title, v.uv_id)"
                                            >
                                                <span class="fas fa-trash-alt me-2"></span>Удалить
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
                <div class="mb-2 mx-0 row">
                    <div class="button-group">
                        <button class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#modalEventsList">Добавить мероприятие</button>
                    </div>
                </div>
            </div>
        </div>
        <user-subscription :user-id="userId" />
        <div class="card card-module mt-4 border-0 shadow components-section">
            <div class="card-body">
                <div class="mx-0 row">
                    <div class="button-group">
                        <button class="btn btn-success text-white" @click="storeEventsList">Сохранить изменения</button>
                        <a href="/admin/users/" class="btn btn-gray-500 text-white ms-2">Назад</a>
                    </div>
                </div>
            </div>
        </div>
        <events-list-modal @data-selected="getCheckEvents" :data-items="events" />
        <modal-delete @delete-confirm="deleteEventItem" :id="event.id" :title="'\u0022'+event.title+'\u0022'"></modal-delete>
        <modal-delete-list @delete-confirm="deleteSelectedEvents" :title="'Удалить отмеченные?'"></modal-delete-list>
    </div>
</template>
<script>
import axios from 'axios';
import EventsListModal from './components/EventsListModal.vue';
import ModalDelete from '../components/ModalDelete.vue';
import ModalDeleteList from '../components/ModalDeleteList.vue';
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';
import UserSubscription from './components/UserSubscription.vue';

export default {
    components: {
        EventsListModal,
        ModalDelete,
        ModalDeleteList,
        UserSubscription
    },
    props: {
        userId: Number
    },
    data: () => ({
        loading: {
            event: {
                active: true,
                progress: false
            }
        },
        events: [],
        eventsList: [],
        eventSelArr: [],
        eventCheckItems: [],
        checkedAllItems: false,
        rangeInpActive: false,
        event: {
            id: null,
            title: null,
            uvid: null
        },
        state: {
            event: {
                success: false,
                error: false,
                info: ""
            }
        },
        errorInp: {
            id: 0
        },
        dateRange: {
            start: null,
            end: null
        },
        deleteBtn: {
            active: false
        }
    }),
    mounted() {
        this.getEvents(this.eventSelArr);
        this.getUserEvents();
    },
    methods: {
        getUserEvents(){
            let user_id = this.userId;
            if (user_id) {
                axios.get('/api/user_event_list/'+user_id)
                .then(res => {
                    this.loading.event.active = false;
                    this.eventsList = res.data.user_events;

                    for(let i in res.data.user_events){
                        this.eventSelArr.push(res.data.user_events[i].id);
                    }

                    this.getEvents(this.eventSelArr);

                    // console.log(res);
                }).catch(err => {
                    this.state.event.error = true;
                    this.state.event.success = false;
                    this.state.event.info = "Ошибка загрузки"
                })
            }
        },
        getEvents(event_not_list){
            const formData = new FormData();
            let json_data = JSON.stringify(event_not_list);
            formData.append('events', json_data);

            axios.post('/api/event_list', formData)
            .then(res => {
                this.loading.event.active = false;
                this.events = res.data.events;
            })
        },
        getCheckEvents(check_events) {
            console.log(check_events);
            const formData = new FormData();
            let json_data = JSON.stringify(check_events);

            formData.append('check_events', json_data);

            axios.post('/api/event_check_list/', formData)
            .then(res => {
                for(let i in res.data.events){
                    // this.eventsList.push(res.data.events[i]);

                    if(this.eventsList.length > 0){
                        let isEventsList = this.eventsList.some((el) => el.id === res.data.events[i].id);
                        if(!isEventsList){
                            this.eventsList.push(res.data.events[i]);
                        }
                        // console.log('isVideoList:', isVideoList);
                    }else{
                        this.eventsList.push(res.data.events[i]);
                    }

                    if(this.eventSelArr.length > 0){
                        if(this.eventSelArr.indexOf(res.data.events[i].id) === -1){
                            this.eventSelArr.push(res.data.events[i].id);
                        }
                    }else{
                        this.eventSelArr.push(res.data.events[i].id);
                    }

                    // this.eventSelArr.push(res.data.events[i].id);
                }

                this.getEvents(this.eventSelArr);
                console.log('eventSelArr:', this.eventSelArr);
            })
        },
        checkItemEvent(event, id){
            if(event.target.checked){
                this.eventCheckItems.push(id);
            }else{
                for(let i in this.eventCheckItems){
                    if(this.eventCheckItems[i] == id){
                        this.eventCheckItems.splice(i, 1);
                    }
                }
            }

            if(this.eventCheckItems.length !== 0){
                this.deleteBtn.active = true;
            }else{
                this.deleteBtn.active = false;
                this.checkedAllItems = false;
            }
            // console.log(this.checkedAllItems);
        },
        checkAllItems(event){
            this.checkedAllItems = event.target.checked;

            if(event.target.checked){
                this.eventCheckItems = [];
                for(let i in this.eventSelArr){
                    this.eventCheckItems.push(this.eventSelArr[i]);
                }
            }else{
                this.eventCheckItems = [];
            }

            if(this.eventCheckItems.length !== 0){
                this.deleteBtn.active = true;
            }else{
                this.deleteBtn.active = false;
            }
        },
        setDatepicker(item, id){

            var user_id = this.userId;

            // if(id){
                axios.get('/api/user_event_date/' + id + '/' + user_id)
                .then(res => {
                    // console.log(res);
                    if(res.data.user_events){
                        let item_name = item.getAttribute('name');
                        let date_start = res.data.user_events.date_start;
                        let date_end = res.data.user_events.date_end;
                        let date_sel;

                        if(item_name == 'date_start'){
                            date_sel = date_start;
                        }else if(item_name == 'date_end'){
                            date_sel = date_end;
                        }

                        let dp = new AirDatepicker(item, {
                            range: false,
                            // multipleDatesSeparator: ' - ',
                            selectedDates: date_sel
                        });
                        dp.show();
                    }else{
                        let dp = new AirDatepicker(item, {
                            range: false
                            // multipleDatesSeparator: ' - '
                        });
                        dp.show();
                    }
                })
            // }

        },
        storeEventsList() {
            const formData = new FormData();
            let user_id = this.userId;
            let range_item = document.querySelectorAll('.range_item_event');
            let range_item_subs = document.querySelectorAll('.range_item_subs');
            let period_subs = document.querySelectorAll('.subs_period');
            let user_event_list = [];
            let user_subs_list = [];

            this.loading.event.progress = true;

            // range_item.forEach(function(elem){
            //     let name_item = elem.getAttribute('name');
            //     let id_item = elem.getAttribute('data-id');
            //     if(name_item == 'date_start') {
            //         user_event_list.push({
            //             id: id_item,
            //             date_start: elem.value
            //         });
            //     }else if(name_item == 'date_end'){
            //         for(let i in user_event_list){
            //             if(user_event_list[i].id === id_item){
            //                 user_event_list[i].date_end = elem.value;
            //             }
            //         }
            //     }
            // })
            //
            // range_item_subs.forEach(function(elem){
            //     let index_item = elem.getAttribute('data-index');
            //     user_subs_list.push({
            //         index: index_item,
            //         date_start: elem.value,
            //         period: ""
            //     })
            // })

            period_subs.forEach(function(elem){
                let index_item = elem.getAttribute('data-index');
                for(let i in user_subs_list){
                    if(user_subs_list[i].index == index_item){
                        user_subs_list[i].period = elem.value
                    }
                }
            })

            // let json_data = JSON.stringify(user_event_list);
            let json_data = JSON.stringify(this.eventSelArr);
            let json_data_subs = JSON.stringify(user_subs_list);

            formData.append('user_events', json_data);
            // formData.append('user_subs', json_data_subs);

            axios.post('/api/event_store/' + user_id, formData, {
                headers: {
                    "Content-type": "application/json"
                }
            })
            .then(res => {
                console.log(res);
                this.loading.event.progress = false;
                if(res.data.status){
                    this.getUserEvents();
                    this.state.event.success = true;
                    this.state.event.error = false;
                    this.state.event.info = 'Успешно сохранено';
                    this.errorInp.id = 0;
                }else{
                    this.state.event.success = false;
                    this.state.event.error = true;
                    if(res.data.error == 1){
                        this.state.event.info = 'Конечная дата не должна быть раньше текущей даты';
                        this.errorInp.id = res.data.event_id
                    }else if(res.data.validate){
                        this.state.event.info = 'Заполните все обязательные поля';
                    }else{
                        this.state.event.info = 'Ошибка сохранения';
                    }
                }
            })

        },
        getEventData(id, title, uvid){
            if(id) this.event.id = id;
            if(title) this.event.title = title;
            if(uvid) this.event.uvid = uvid;
        },
        deleteEventItem(id){
            if(id){
                // console.log(this.videoList);
                for(let i in this.eventsList){
                    if(this.eventsList[i].id == id){
                        this.eventsList.splice(i, 1);
                    }
                }
                for(let i in this.eventSelArr){
                    if(this.eventSelArr[i] == id){
                        this.eventSelArr.splice(i, 1);
                    }
                }
                this.getEvents(this.eventSelArr);
                this.event.id = null;
                this.event.title = null;

                if(this.event.uvid){
                    axios.post('/api/user_event_delete/'+this.event.uvid)
                    .then(res => {
                        // console.log(res);
                    });
                }
            }
        },
        deleteSelectedEvents(){
            if(this.eventCheckItems.length !== 0){
                const formData = new FormData();
                let user_id = this.userId;
                let json_data = JSON.stringify(this.eventCheckItems);

                formData.append('events', json_data);

                // console.log(this.videoList);

                axios.post('/api/user_event_delete_selected/' + user_id, formData)
                .then(res => {
                    // console.log(res);
                    for(let n in this.eventCheckItems){
                        let item_id = this.eventCheckItems[n];
                        for(let i in this.eventsList){
                            if(this.eventsList[i].id == item_id){
                                this.eventsList.splice(i, 1);
                            }
                        }
                        for(let i in this.eventSelArr){
                            if(this.eventSelArr[i] == item_id){
                                this.eventSelArr.splice(i, 1);
                            }
                        }
                    }
                    this.getEvents(this.eventSelArr);
                })
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
.range_item {
    padding: 5px 8px;
    border: 1px solid transparent;
    /* border: 1px solid #f3f6f9; */
    background-color: rgba(17, 24, 39, 0.075);
}
.range_item.active {
    border: 1px solid #e2f0ff;
    background-color: #e2f0ff;
}
.table-hover > tbody > tr:hover .range_item {
    background-color: #fff;
}
.error-inp {
    border: 1px solid #f00;
}
</style>
