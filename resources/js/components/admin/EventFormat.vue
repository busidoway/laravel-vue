<template>
    <div class="event-format">
        <div class="row mt-2">
            <div class="col-xl-6 user-video__list">
                <div class="mb-4" v-for="(format, index) in formatList" :key="index">
                    <div class="mb-2 d-flex justify-content-between"><span>{{ format.title }}</span><a class="text-danger" @click.prevent="deleteFormat(format.id)" title="Удалить"><i class="fas fa-trash-alt me-2"></i></a></div>
                    <input type="hidden" :name="'event_format['+ format.id +'][]'" :value="format.id">
                    <div class="">
                        <input type="text" class="form-control" :name="'event_format['+ format.id +'][]'" placeholder="Дополнительный текст">
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_event_format" @click.prevent>Выбрать формат</button>
        </div>
        <div class="modal fade" id="modal_event_format" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="eventFormatList" action="">
                    <div class="modal-header text-center">
                        <h2 class="h4 modal-title">Форматы</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-2">
                        <div class="border-bottom modal-video-list__item p-2 d-flex justify-content-between align-items-center" v-for="v in checkFormatList" :key="v.id">
                            <label :for="'format_item_' + v.id" class="m-0 w-100">{{ v.title }}</label>
                            <input type="checkbox" name="format_item" class="form-check-input" :id="'format_item_' + v.id" :value="v.id" v-model="checkFormat">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-success text-white" data-bs-dismiss="modal" @click.prevent="getCheckFormat">Применить</button>
                        <button type="button" class="btn btn-gray-500 text-white ms-auto" data-bs-dismiss="modal">Отмена</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        props: {
            eventId: {
                type: Number,
                default:0
            }
        },
        data: () => ({
            formatList: [],
            checkFormat: [],
            checkFormatList: [],
            formatSelArr: [],
            state: {
                success: false,
                error: false,
                info: ""
            }
        }),
        computed: {
            
        },
        mounted() {
            this.getFormatList(this.eventId);
        },
        methods: {
            getFormatList(id) {
                
                const formData = new FormData();
                
                if(!id) id = null;
                
                formData.append('id', id);

                axios.post('/api/event_format/', formData)
                .then(res => {
                    // this.loading = false;
                    this.formatList = res.data.event_format_list;
                    // console.log(this.formatList);

                    for(let i in res.data.event_format_list){
                        this.formatSelArr.push(res.data.event_format_list[i].id);
                    }

                    this.getFormatCheckList(this.formatSelArr);

                    // this.checkFormatList = res.data.event_format_checklist;
                    // console.log(this.checkFormatList);
                }).catch(err => {
                    this.state.error = true;
                    this.state.success = false;
                    this.state.info = "Ошибка загрузки"
                })
            },
            getFormatCheckList(format_not_list){
                const formData = new FormData();
                let json_data = JSON.stringify(format_not_list);
                formData.append('format_not_list', json_data);

                axios.post('/api/event_format', formData)
                .then(res => {
                    this.checkFormatList = res.data.event_format_checklist;
                })
            },
            getCheckFormat() {
                // console.log(this.checkFormat);

                // for(let i in this.checkFormat){
                //     let item = this.checkFormat[i];
                //     if(){
                    
                //     }
                //     this.formatList.push(this.checkFormatList[item]);
                // }

                for(let i in this.checkFormatList){
                    for(let n in this.checkFormat){
                        if(this.checkFormatList[i].id == this.checkFormat[n]){
                            this.formatList.push(this.checkFormatList[i]);
                            this.checkFormatList.splice(i, 1);
                        }
                    }
                }

                this.checkFormat = [];

                // const formData = new FormData();
                // let check_format = JSON.stringify(this.checkFormat);
                // let format_list = JSON.stringify(this.formatList);
                // let check_format_list = JSON.stringify(this.checkFormatList);
                
                // formData.append('check_format', check_format);
                // formData.append('format_list', format_list);
                // formData.append('check_format_list', check_format_list);

                // // console.log(formData);

                // axios.post('/api/check_event_format/', formData)
                // .then(res => {

                //     if(res.data.event_format.length !== 0){
                //         this.checkFormatList = res.data.event_format;
                //     }

                //     console.log(this.checkFormatList);

                // }).catch(err => {
                //     this.state.error = true;
                //     this.state.success = false;
                //     this.state.info = "Ошибка загрузки"
                // })
            },
            deleteFormat(id) {

                for(let i in this.formatList){
                    if(this.formatList[i].id == id){
                        this.checkFormatList.push(this.formatList[i]);
                        this.formatList.splice(i, 1);
                    }
                }
                // for(let i in this.checkFormat){
                //     if(this.checkFormat[i] == id){
                //         this.checkFormat.splice(i, 1);
                //     }
                // }

                // console.log(this.checkFormatList);
            }
        }
    }
</script>