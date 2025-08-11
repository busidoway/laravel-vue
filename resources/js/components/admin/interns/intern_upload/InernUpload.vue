<template>
    <div class="InternUpload">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page :title="navTitle" :nav-list="dataNav" />
        </div>
        <div class="card card-body border-0 shadow">
            <div class="mb-4" v-if="uploadRowsCount">
                <div class="py-2 fs-5 text-success">Данные загружены</div>
                <div class="upload-reestr__upload-info">
                    <span v-if="uploadRowsCount">Загружено: {{ uploadRowsCount }},</span>
                    <a
                        class="text-danger ms-2 errors-count"
                        href="#error_reestr_upload"
                        v-if="invalidRowsCount"
                    >
                        Ошибки: {{ invalidRowsCount }}
                    </a>
                    <a
                        class="ms-2"
                        v-else
                    >
                        Ошибки: 0
                    </a>
                </div>
            </div>
            <div>
                <div class="mb-5 col-xl-4">
                    <file-upload-list :title="'Файл для загрузки (.xlsx)'" :multiple="false" @data-files="getDataFiles" />
                </div>
                <div class="mb-5">
                    <input type="checkbox" name="check" id="check" class="form-check-input me-2" v-model="checkTruncate" >
                    <label class="form-check-label" for="check">Очистить таблицу пользователей РосНОУ</label>
                    <div class="text-danger ms-4 ps-1" style="font-size:14px;">Внимание: если данный параметр включен, вся таблица будет очищена и загрузятся новые данные из выбранного файла.
                        <br>Если параметр отключен, то к существующим записям добавятся данные из выбранного файла.</div>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <button class="btn btn-gray-500 text-white" @click.prevent="reloadPage">Сбросить</button>
                    <button class="btn btn-success text-white ms-2" @click.prevent="uploadData" :disabled="disabledUploadBtn">Загрузить</button>
                    <div class="spinner-border text-gray-400 ms-3" role="status" v-if="loaderSend">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body border-0 shadow mt-3 mb-5" v-if="invalidRows.length > 0">
            <div class="error-reestr-upload" id="error_reestr_upload">
                <div class="text-danger mb-3 error-reestr-upload__title">Ошибки при загрузке:</div>
                <div class="error-reestr-upload__content">
                    <div class="error-reestr-upload__item" v-for="item in invalidRows" :key="item.id">
                        <div class="error-reestr-upload__item-error" v-for="error in item.errors">{{ error }}</div>
                        <div class="error-reestr-upload__item-info">Строка {{ item.row }}: <span class="error-reestr-upload__item-email text-info">{{ item.email }}</span></div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import NavPage from "../../components/NavPage.vue";
import FileUploadList from "../../components/FileUploadList.vue";
export default {
    components: {
        NavPage,
        FileUploadList
    },
    data: () => ({
        navTitle: "Загрузка пользователей",
        dataNav: [],
        dataFiles: [],
        uploadRowsCount: 0,
        invalidRowsCount: 0,
        invalidRows: [],
        checkTruncate: true,
        loaderSend: false,
        disabledUploadBtn: true,
    }),
    methods: {
        getDataFiles(files) {
            if (files.length > 0) {
                this.dataFiles = files;
                this.disabledUploadBtn = false
            } else {
                this.disabledUploadBtn = true
            }
        },
        uploadData() {
            if (this.dataFiles.length > 0) {

                const formData = new FormData();

                formData.append('file', this.dataFiles[0]);
                formData.append('check', this.checkTruncate);

                this.loaderSend = true;

                axios.post('/api/intern_upload_data',
                    formData,
                    {
                        headers: {'Content-Type': 'multipart/form-data'}
                    }
                )
                .then(resp => {
                    this.loaderSend = false;
                    if (resp.data.status) {
                        if (resp.data.invalid_rows_count) this.invalidRowsCount = resp.data.invalid_rows_count;
                        if (resp.data.upload_rows_count) this.uploadRowsCount = resp.data.upload_rows_count;
                        if (resp.data.invalid_rows.length > 0) this.invalidRows = resp.data.invalid_rows;
                    }
                })
                .catch(err => {
                    console.log(err);
                    this.loaderSend = false;
                })

            }
        },
        reloadPage() {
            document.location.reload();
        }
    }
}
</script>
