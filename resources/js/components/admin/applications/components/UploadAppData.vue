<template>
    <div class="upload-app-data d-flex align-items-center">
        <div class="text-danger me-3" v-if="error">{{ error }}</div>
        <div class="spinner-border text-info me-3" role="status" v-if="loading">
            <span class="visually-hidden">Loading...</span>
        </div>
        <a v-if="fileLink" :href="fileLink" class="me-3 btn btn-outline-info" title="Скачать" :download="fileName + fileType">
            <i class="fas fa-cloud-download-alt"></i>
            <span class="ms-1">
                {{ fileName }}{{ fileType }}
            </span>
        </a>
        <button class="btn btn-success text-white" @click.prevent="uploadAppData" :disabled="loading">
            <i class="fas fa-file-upload"></i>
            <span class="ms-1">Выгрузить в Excel</span>
        </button>
    </div>
</template>

<script>
import axios from "axios";
export default {
    props: {
        appData: Array,
        eventId: Number
    },
    data: () => ({
        loading: false,
        fileLink: null,
        fileName: null,
        fileType: ".xlsx",
        error: null
    }),
    methods: {
        uploadAppData() {
            const formData = new FormData();
            const jsonData = JSON.stringify(this.appData);
            formData.append('data', jsonData);
            if(this.eventId) formData.append('event_id', this.eventId);

            this.loading = true;

            axios.post('/api/applications_upload_data', formData)
                .then(resp => {
                    let currDate = new Date();
                    this.loading = false;
                    this.error = null;
                    if(resp.data.app) {
                        let fileNameText = '';
                        if(resp.data.event.string !== 0)
                            fileNameText = resp.data.event.date_public + '_' + resp.data.event.title.replace(/(<([^>]+)>)/gi, '');
                        else
                            fileNameText = 'Отчет_по_заявкам';

                        this.fileLink = JSON.parse(resp.data.app);
                        this.fileName = fileNameText + '_' +
                                        currDate.getFullYear() +
                                        '-' + currDate.getMonth() +
                                        '-' + currDate.getDate() +
                                        '-' + currDate.getHours() +
                                        currDate.getMinutes() +
                                        currDate.getSeconds();
                    }else{
                        this.error = "Невозможно выгрузить данные";
                    }
                }).catch(err => {
                    this.loading = false;
                    this.error = err;
                })
        }
    }
}
</script>
