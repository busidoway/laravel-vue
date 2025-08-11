<template>
    <div class="modal-video-list modal fade" id="modalVideoList" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <!-- <div class="modal-vue"> -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> -->
                    <div class="modal-body p-0">
                        <div class="card p-3 p-lg-4">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h4">Список видео</h1>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between border-bottom modal-video-list__header">
                                    <div class="fw-bold">Наименование</div>
                                    <!-- <div class=""><input type="checkbox" name="all_video_item" class="form-check-input" id="all_video_item" @change="checkAllItem($event)"></div> -->
                                </div>
                                <div class="border-bottom modal-video-list__item" v-for="v in videoList" :key="v.id">
                                    <label :for="'video_item_' + v.id" class="m-0">{{ v.title }}</label>
                                    <input type="checkbox" name="video_item" class="form-check-input" :id="'video_item_' + v.id" :value="v.id" v-model="checkVideos">
                                </div>
                            </div>
                            <!-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Understood</button>
                            </div> -->
                            <div class="button-group">
                                <button class="btn btn-success text-white" @click.prevent="getCheckVideos" data-bs-dismiss="modal">Выбрать</button>
                                <button class="btn btn-gray-500 text-white ms-3" data-bs-dismiss="modal">Отмена</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div>
        <div class="modal-backdrop fade show"></div> -->
    </div>
</template>
<script>
export default {
    props: {
        videoList: Array
    },
    data: () => ({
        videos: [],
        checkVideos: []
    }),
    mounted() {
        
    },
    methods: {
        closeModal(){
            this.$emit('close-modal', true)
        },
        getCheckVideos(){
            // console.log(this.checkVideos);
            this.$emit('check-videos', this.checkVideos)
            // this.$emit('close-modal', true)
        },
        checkAllItem(event){
            this.checkVideos = event.target.checked;
            // console.log(this.checkVideos);
            // this.$emit('check-videos', this.checkVideos)
        }
    }
}
</script>
<style scoped>
.modal-vue {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0;
    transition: opacity 0.15s linear;
}
.modal-video-list__header {
    color:#9d9d9d;
    padding: 14px 10px;
}
.modal-video-list__item {
    display: grid;
    grid-template-columns: 1fr auto;
    padding: 14px 10px;
}
</style>