<template>
    <div class="event-video">
        <div class="event-video-list">
            <table class="table table-centered table-nowrap mb-0 rounded table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" id="video_check_all" name="video_check_all" class="form-check-input" @change="checkAllItems($event)" :checked="checkedAllItems" style="height:15.75px;width:15.75px;">
                            </div>
                        </th>
                        <th class="border-0">#</th>
                        <th class="border-0">Заголовок</th>
                        <th class="border-0">Раздел</th>
                        <th class="border-0">Длительность</th>
                        <th class="border-0"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="v in videoList" :key="v.id">
                        <td class="align-middle">
                            <input type="checkbox" :id="'video_check_item'+ v.id" name="video_check_item" class="form-check-input" :value="v.id" v-model="videoCheckItems">
                            <input type="hidden" :name="'event_video[]'" :value="v.id">
                        </td>
                        <td class="align-middle">
                            {{ v.id }}
                        </td>
                        <td class="align-middle">
                            {{ v.title }}
                        </td>
                        <td class="align-middle">
                            {{ v.cat }}
                        </td>
                        <td class="align-middle">
                            {{ v.time }}
                        </td>
                        <td class="align-middle">
                            <div class="btn-group m-0">
                                <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="icon icon-sm">
                                        <span class="fas fa-ellipsis-h icon-dark"></span>
                                    </span>
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu py-0">
                                    <a class="dropdown-item" :href="'/admin/video/'+ v.id +'/edit'"><span class="fas fa-edit me-2"></span>Перейти в редактирование</a>
                                    <button type="button" class="dropdown-item text-danger rounded-bottom" data-bs-toggle="modal" data-bs-target="#modal-delete" @click.prevent="getVideoData(v.id, v.title, v.uv_id)">
                                        <span class="fas fa-trash-alt me-2"></span>Удалить</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVideoList">Добавить видео</button>
        <button type="button" class="ms-2 btn btn-success text-white">[+] Новое видео</button>
        <modal-video-list @check-videos="getCheckVideos" :video-list="videos" />
        <modal-delete @delete-confirm="deleteVideoItem" :id="video.id" :title="'\u0022'+video.title+'\u0022'"></modal-delete>
    </div>
</template>

<script>
    import axios from 'axios';
    import ModalVideoList from './components/ModalVideoList.vue';
    import ModalDelete from './components/ModalDelete.vue';
    import ModalDeleteList from './components/ModalDeleteList.vue';

    export default {
        components: { 
            ModalVideoList,
            ModalDelete,
            ModalDeleteList
        },
        props: {
            eventId: Number
        },
        data: () => ({
            loading: true,
            loadingProgress: false,
            modal: {
                video: false
            },
            video: {
                id: null,
                title: null,
                uvid: null
            },
            videos: [],
            videoList: [],
            videoSelArr: [],
            videoCheckItems: [],
            checkedAllItems: false,
            state: {
                success: false,
                error: false,
                info: ""
            },
        }),
        mounted() {
            this.getEventVideos();
            this.getVideos(this.videoSelArr);
        },
        methods: {
            getEventVideos(){
                var event_id = this.eventId;
                if(event_id){
                    axios.get('/api/event_video_list/' + event_id)
                    .then(res => {
                        this.loading = false;
                        this.videoList = res.data.event_video;

                        for(let i in res.data.event_video){
                            this.videoSelArr.push(res.data.event_video[i].id);
                        }

                        this.getVideos(this.videoSelArr);

                        // console.log('videoSelArr0:', this.videoSelArr);
                    }).catch(err => {
                        this.state.error = true;
                        this.state.success = false;
                        this.state.info = "Ошибка загрузки"
                    })
                }
            },
            getVideos(video_not_list){
                const formData = new FormData();
                let json_data = JSON.stringify(video_not_list);
                formData.append('videos', json_data);

                axios.post('/api/video_list', formData)
                .then(res => {
                    // console.log(res.data.videos.data);
                    this.loading = false;
                    this.videos = res.data.videos.data;
                })
            },
            getCheckVideos(check_videos){
                // console.log('check_videos:', check_videos);
                const formData = new FormData();
                let json_data = JSON.stringify(check_videos);
                
                formData.append('check_videos', json_data);

                axios.post('/api/video_check_list/', formData)
                .then(res => {
                    // console.log('res.data.videos:', res.data.videos);
                    // this.videoList = res.data.videos;
                    // this.videoSelArr = [];
                    // let newSelArr = [];
                    // console.log('videoList1:', this.videoList);

                    for(let i in res.data.videos){
                        if(this.videoList.length > 0){
                            let isVideoList = this.videoList.some((el) => el.id === res.data.videos[i].id);
                            if(!isVideoList){
                                this.videoList.push(res.data.videos[i]);
                            }
                            // console.log('isVideoList:', isVideoList);
                        }else{
                            this.videoList.push(res.data.videos[i]);
                        }

                        if(this.videoSelArr.length > 0){
                            if(this.videoSelArr.indexOf(res.data.videos[i].id) === -1){
                                this.videoSelArr.push(res.data.videos[i].id);
                            }
                        }else{
                            this.videoSelArr.push(res.data.videos[i].id);
                        }
                        
                        // this.videoSelArr.push(res.data.videos[i].id);
                    }

                    // console.log('newSelArr:', newSelArr);

                    // console.log('videoList:', this.videoList);
                    // console.log('videoList2:', this.videoList);

                    this.getVideos(this.videoSelArr);
                })
            },
            checkAllItems(event){
                this.checkedAllItems = event.target.checked;

                if(event.target.checked){
                    this.videoCheckItems = [];
                    for(let i in this.videoSelArr){
                        this.videoCheckItems.push(this.videoSelArr[i]);
                    }
                }else{
                    this.videoCheckItems = [];
                }

                if(this.videoCheckItems.length !== 0){
                    this.deleteBtn.active = true;
                }else{
                    this.deleteBtn.active = false;
                }
            },
            getVideoData(id, title, uvid){
                if(id) this.video.id = id;
                if(title) this.video.title = title;
                if(uvid) this.video.uvid = uvid;
            },
            deleteVideoItem(id){
                if(id){
                    // console.log(this.videoList);
                    for(let i in this.videoList){
                        if(this.videoList[i].id == id){
                            this.videoList.splice(i, 1);
                        }
                    }
                    for(let i in this.videoSelArr){
                        if(this.videoSelArr[i] == id){
                            this.videoSelArr.splice(i, 1);
                        }
                    }
                    this.getVideos(this.videoSelArr);
                    this.video.id = null;
                    this.video.title = null;

                    // console.log('videoList:', this.videoList);
                    // console.log('videoSelArr:', this.videoSelArr);

                    if(this.video.uvid){
                        axios.post('/api/event_video_delete/'+this.video.uvid)
                        .then(res => {
                            // console.log(res);
                        });
                    }
                }
            },
        }
    }
</script>