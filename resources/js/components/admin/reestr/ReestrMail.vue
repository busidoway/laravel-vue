<template>
    <div class="applications-page">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <NavPage title="Рассылка письма" :nav-list="dataNav" />
        </div>
        <div class="card card-module border-0 shadow components-section">
            <div class="card-body">
                <div class="row mb-4 mx-0">
                    <label for="reestr_items">Контакты для рассылки: {{ dataItems.length }}</label>
                    <div class="">
                        <a href="" class="btn btn-outline-tertiary" data-bs-toggle="modal" data-bs-target="#modalReestrContacts"><i class="fas fa-list me-2"></i><span>Список контактов</span></a>
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="header">Заголовок</label>
                    <div class="input-group">
                        <input name="text" id="header" class="form-control" placeholder="Заголовок" v-model="headerMess">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="textMessage">Текст</label>
                    <div class="">
                        <textarea name="text" id="textMessage" class="form-control" rows="30" placeholder="Текст" required></textarea>
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <file-upload-list :title="'Прикрепленные файлы'" :multiple="true" @data-files="getDataFiles" />
                </div>
                <div class="mx-0 mb-3 row">
                    <div class="button-group d-flex align-items-center">
                        <button @click.prevent="returnBack" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i>Назад</button>
                        <button class="btn btn-success text-white ms-3" @click.prevent="sendMail">{{ textButtonSend }}</button>
                        <div class="spinner-border text-gray-400 ms-3" role="status" v-if="loaderSend">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div :class="[(response.status == 'success') ? 'text-success' : (response.status == 'error') ? 'text-danger' : '', 'response-info ms-4']" v-if="response.info">
                            {{ response.info }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <reestr-contacts
            :data-contacts="dataItems"
            :data-error="errorContacts"
            @delete-contact-item="deleteContactItem"
            @delete-contact-data="deleteContactData"
            @close-modal="closeModalContacts"
        />
    </div>
</template>

<script>
import NavPage from "../components/NavPage.vue";
import FileUploadList from "../components/FileUploadList.vue";
import ReestrContacts from "./components/ReestrContacts.vue";
import axios from "axios";
import { mapState, mapActions } from "vuex";

export default {
    components: {
        ReestrContacts,
        FileUploadList,
        NavPage
    },
    props: {
        reestrItemsId: String
    },
    data() {
        return {
            dataNav: [
                {
                    title: 'Реестр',
                    path: '/admin/reestr'
                }
            ],
            dataItems: [],
            headerMess: "",
            textMess: "",
            dataFiles: [],
            images: [],
            response: {
                status: "",
                info: ""
            },
            loaderSend: false,
            textButtonSend: "Отправить",
            errorContacts: ""
        };
    },
    computed: {
        ...mapState({
            dataReestrId: state => state.reestr.dataItems
        })
    },
    watch: {
        dataReestrId(newValue, oldValue) {
            // Вызов метода getReestrItems при изменении dataReestrId
            this.getReestrItems();
        }
    },
    mounted() {
        this.tinymceInit();
        const savedData = sessionStorage.getItem('dataReestrItems');
        if (this.dataReestrId.length > 0) {
            this.setDataReestrItems(this.dataReestrId);
        }else if (savedData) {
            this.setDataReestrItems(JSON.parse(savedData));
        }
        this.getReestrItems();
    },
    methods: {
        // ...mapActions(['setDataItems']),
        sendMail() {
            const formData = new FormData();
            const headerMess = JSON.stringify(this.headerMess);
            const textMessage = tinymce.get('textMessage').getContent();
            const textMessJSON = JSON.stringify(textMessage);
            const dataContactsId = JSON.stringify(this.dataItems.map( (el) => {
                return {'id': el.id}
            }));

            formData.append('header', headerMess);
            formData.append('data', dataContactsId);
            formData.append('message', textMessJSON);

            formData.append('images', JSON.stringify(this.images));

            // for (let i = 0; i < this.dataFiles.length; i++) {
            //     let file = this.dataFiles[i];
            //     formData.append('files[' + i + ']', file);
            // }

            for (let i = 0; i < this.dataFiles.length; i++) {
                formData.append('files[]', this.dataFiles[i]); // используйте files[] вместо files[i]
            }

            this.loaderSend = true;

            axios.post('/api/reestr_send_mail',
                formData,
                {
                    headers: {'Content-Type': 'multipart/form-data'}
                }
            ).then(
                resp => {
                    this.loaderSend = false;
                    if (resp.data.result) {
                        if (resp.data.result.status) this.response.status = resp.data.result.status;
                        if (resp.data.result.info) this.response.info = resp.data.result.info;

                        if (resp.data.result.status == 'success') {
                            this.textButtonSend = 'Отправить повторно';
                        }
                    }
                }
            ).catch(err => {
                this.loaderSend = false;
                this.response.status = 'error';
                this.response.info = err;
            });
        },
        getReestrItems() {
            if(this.dataReestrId.length > 0) {
                const dataItemsId = this.dataReestrId.map(function (el) {
                    return el.id
                })
                const formData = new FormData();
                const dataId = JSON.stringify(dataItemsId);
                formData.append('data', dataId);

                axios.post('/api/reestr_contacts', formData).then(
                    resp => {
                        // console.log(resp.data.result);
                        this.dataItems = resp.data.result;
                    }
                );
            }
        },
        setDataReestrItems(dataItems) {
            this.$store.dispatch('setDataItems', dataItems);
            sessionStorage.setItem('dataReestrItems', JSON.stringify(dataItems));
        },
        deleteContactItem(id) {
            this.errorContacts = "";
            if (this.dataReestrId.length > 1) {
                this.$store.dispatch('deleteDataItem', id);
                sessionStorage.setItem('dataReestrItems', JSON.stringify(this.dataReestrId));
            } else {
                this.errorContacts = "Список контактов не должен быть пустым"
            }
        },
        deleteContactData(data){
            this.errorContacts = "";
            if (this.dataReestrId.length <= 1 || data.length === this.dataReestrId.length) {
                this.errorContacts = "Список контактов не должен быть пустым"
            } else if (this.dataReestrId.length > 1) {
                if(data.length > 0) {
                    this.$store.dispatch('deleteData', data);
                    sessionStorage.setItem('dataReestrItems', JSON.stringify(this.dataReestrId));
                }
            }
        },
        closeModalContacts() {
            this.errorContacts = "";
        },
        getDataFiles(files) {
            if (files.length > 0) this.dataFiles = files;
        },
        tinymceInit() {

            const handleImageUpload = async (blobInfo, progress, failure) => {
                const formData = new FormData();
                formData.append("file", blobInfo.blob(), blobInfo.filename());

                try {
                    const response = await axios.post(
                        "/api/image_upload",
                        formData,
                        {
                            headers: { "Content-Type": "multipart/form-data" },
                            onUploadProgress: (progressEvent) => {
                                const percentCompleted = Math.round(
                                    (progressEvent.loaded * 100) / progressEvent.total
                                );
                                if (progress && typeof progress === "function") {
                                    progress(percentCompleted);
                                }
                            },
                        }
                    );

                    if (response.status === 403) {
                        throw new Error("HTTP Error: " + response.status);
                    }

                    if (response.status < 200 || response.status >= 300) {
                        throw new Error("HTTP Error: " + response.status);
                    }

                    const json = response.data;

                    if (!json || typeof json.location !== "string") {
                        throw new Error("Invalid JSON: " + JSON.stringify(json));
                    } else response;
                    {
                        const imageUrl = json.location;
                        this.images.push(imageUrl);
                        return json.location;
                    }
                } catch (error) {
                    if (failure && typeof failure === "function") {
                        failure(error.message);
                    }
                }
            };

            tinymce.remove('#textMessage');
            tinymce.init({
                license_key: "gpl",
                promotion: false,
                selector: '#textMessage',
                language: "ru",
                plugins: [
                    "autolink", "lists", "link", "charmap", "preview", "code", "image", "fullscreen",
                    "table", "advlist", "visualblocks", "searchreplace"
                ],
                toolbar: [
                    "newdocument cut copy paste bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | image | link unlink anchor | removeformat code | preview fullscreen",
                    "customBtnLName customBtnName customBtnMName customBtnEmail customBtnDateEnd customBtnDateStart"
                ],
                toolbar_mode: 'sliding',
                remove_script_host: false,
                relative_urls: false,
                automatic_uploads: true,
                images_upload_handler: handleImageUpload,
                setup: function (editor) {
                    editor.ui.registry.addButton('customBtnLName', {
                        text: '{фамилия}',
                        tooltip: 'Фамилия',
                        onAction: function (_) {
                            editor.insertContent('{фамилия}');
                        }
                    });
                    editor.ui.registry.addButton('customBtnName', {
                        text: '{имя}',
                        tooltip: 'Имя',
                        onAction: function (_) {
                            editor.insertContent('{имя}');
                        }
                    });
                    editor.ui.registry.addButton('customBtnMName', {
                        text: '{отчество}',
                        tooltip: 'Отчество',
                        onAction: function (_) {
                            editor.insertContent('{отчество}');
                        }
                    });
                    editor.ui.registry.addButton('customBtnEmail', {
                        text: '{email}',
                        tooltip: 'Электронная почта',
                        onAction: function (_) {
                            editor.insertContent('{email}');
                        }
                    });
                    editor.ui.registry.addButton('customBtnDateEnd', {
                        text: '{срок аттестата}',
                        tooltip: 'Срок аттестата',
                        onAction: function (_) {
                            editor.insertContent('{срок аттестата}');
                        }
                    });
                    editor.ui.registry.addButton('customBtnDateStart', {
                        text: '{дата выдачи аттестата}',
                        tooltip: 'Дата выдачи аттестата',
                        onAction: function (_) {
                            editor.insertContent('{дата выдачи аттестата}');
                        }
                    });
                }
            });
        },
        returnBack() {
            this.clearSessionStorage();
            this.$router.push({name: 'Reestr'});
        },
        clearSessionStorage() {
            sessionStorage.removeItem('dataReestrItems');
        }
    },
    beforeRouteLeave(to, from, next) {
        this.clearSessionStorage();
        next();
    }
}
</script>

<style>
.tox .tox-toolbar .tox-tbtn__select-label {
    cursor: pointer;
}

.response-info.text-danger {
    background-color: rgb(245 163 181 / 30%);
    padding: 7px 12px;
    border-radius: 6px;
}

.response-info.text-success {
    background-color: #e0ffe3;
    padding: 7px 12px;
    border-radius: 6px;
}
</style>
