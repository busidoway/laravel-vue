<template>
    <div class="organizations-edit">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page :title="navTitle" :nav-list="dataNav" />
        </div>
        <div class="card card-module border-0 shadow components-section">
            <div class="card-body">
                <div class="mb-4 text-uppercase h5 ps-2 fw-bolder">Данные организации</div>
                <div class="row mb-4 mx-0">
                    <label for="name">Наименование *</label>
                    <div class="input-group">
                        <input name="text" id="name" class="form-control" v-model="name">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="name_short">Краткое наименование *</label>
                    <div class="input-group">
                        <input name="name_short" id="name_short" class="form-control" v-model="nameShort">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="name_full">Наименование для фильтра *</label>
                    <div class="input-group">
                        <input name="name_filter" id="name_filter" class="form-control" v-model="nameFilter">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="name_full">Полное наименование</label>
                    <div class="input-group">
                        <input name="name_full" id="name_full" class="form-control" v-model="nameFull">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="header">Email *</label>
                    <div class="input-group">
                        <input name="email" id="email" class="form-control" v-model="email">
                    </div>
                </div>
                <div class="row mb-4 mx-0 col-md-6">
                    <label for="city">Город *</label>
                    <div class="input-group">
                        <select name="city" id="city" class="form-select" v-model="city">
                            <option v-for="item in dataCities" :value="item.id" :key="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="num_cert">№ свидетельства об аккредитации</label>
                    <div class="input-group">
                        <input name="num_cert" id="num_cert" class="form-control" v-model="numCert">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <div class="col-md-4">
                        <label for="date_start">Начало аккредитации</label>
                        <div class="input-group">
                            <input type="date" name="date_start" id="date_start" class="form-control" v-model="dateStart">
                        </div>
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <div class="col-md-4">
                        <label for="date_end">Окончание аккредитации</label>
                        <div class="input-group">
                            <input type="date" name="date_end" id="date_end" class="form-control" v-model="dateEnd">
                        </div>
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <file-upload-list :title="'Логотип'" :multiple="false" @data-files="getDataFiles" />
                </div>
                <div class="row mb-4 mx-0">
                    <label for="description">Описание</label>
                    <div class="">
                        <textarea name="description" id="description" class="form-control" rows="6" v-model="description"></textarea>
                    </div>
                </div>
                <div class="mb-4 mt-5 text-uppercase h5 ps-2 fw-bolder">Программы</div>
                <div class="row mb-4 mx-0">
                    <label for="num_doc" class="mb-4">Перечень программ</label>
                    <div class="org-program-list">
                        <div class="row mb-4 mx-0" v-for="(item, index) in programsOrgList" :key="index">
                            <div class="input-group px-0">
                                <input type="text" :name="'program'+index" :id="'program'+index" class="form-control" placeholder="" v-model="programsOrgList[index]">
                                <button class="text-danger ms-1 btn py-0 px-2" @click.prevent="deleteOrgProgram(index)"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <div class="row mb-3 mx-0">
                            <div class="button-group px-0">
                                <button class="btn btn-primary" @click.prevent="addOrgProgram">[+] Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 mt-5 text-uppercase h5 ps-2 fw-bolder">Контакты</div>
                <div class="row mb-4 mx-0">
                    <label for="manager">Менеджер</label>
                    <div class="input-group">
                        <input type="text" name="manager" id="manager" class="form-control" v-model="manager">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="manager">Веб-сайт</label>
                    <div class="input-group">
                        <input type="text" name="website" id="website" class="form-control" v-model="website">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="phone">Телефон</label>
                    <div class="input-group">
                        <input type="text" name="phone" id="phone" class="form-control" v-model="phone">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="phone">Адрес</label>
                    <div class="input-group">
                        <input type="text" name="address" id="address" class="form-control" v-model="address">
                    </div>
                </div>
                <div class="row mb-4 mx-0">
                    <label for="boss">Руководитель</label>
                    <div class="input-group">
                        <input type="text" name="boss" id="boss" class="form-control" v-model="boss">
                    </div>
                </div>
                <div class="mb-4 mt-5 text-uppercase h5 ps-2 fw-bolder">Подразделение</div>
                <div class="row mb-4 mx-0">
                    <label for="subdiv">Описание подразделения</label>
                    <div class="">
                        <textarea name="subdiv" id="subdiv" class="form-control" rows="6" v-model="subdiv"></textarea>
                    </div>
                </div>
                <div class="mb-4 px-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="hiiden_program" id="check_hidden_program" v-model="hiddenProgram">
                        <label class="form-check-label" for="check_hidden_program">Не отображать в программах</label>
                    </div>
                </div>
                <div class="mb-4 px-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="hidden_reestr" id="check_hidden_reestr" v-model="hiddenReestr">
                        <label class="form-check-label" for="check_hidden_reestr">Не отображать в реестре</label>
                    </div>
                </div>
                <div class="mb-4 px-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="hidden_more" id="check_hidden_more" v-model="hiddenMore">
                        <label class="form-check-label" for="check_hidden_more">Отключить подробный просмотр</label>
                    </div>
                </div>
                <div class="row mb-4 mx-0 ps-3" style="font-size:14px;">
                    * Обязательно для заполнения
                </div>
                <div class="mx-0 mb-3 row">
                    <div class="button-group d-flex align-items-center">
                        <button @click.prevent="returnBack" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i>Назад</button>
                        <button class="btn btn-success text-white ms-3" @click.prevent="saveData">{{ textButtonSend }}</button>
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
    </div>
</template>

<script>
import NavPage from "../components/NavPage.vue";
import axios from "axios";
import FileUploadList from "../components/FileUploadList.vue";
export default {
    components: {
        FileUploadList,
        NavPage
    },
    data: () => ({
        navTitle: "Создание организации",
        dataNav: [
            {
                title: 'Организации',
                path: '/admin/organizations/'
            },
        ],
        dataCities: [],
        name: "",
        email: "",
        description: "",
        city: null,
        dataFiles: [],
        nameShort: "",
        nameFull: "",
        nameFilter: "",
        numCert: "",
        dateStart: "",
        dateEnd: "",
        manager: "",
        website: "",
        phone: "",
        address: "",
        boss: "",
        subdiv: "",
        hiddenProgram: false,
        hiddenReestr: false,
        hiddenMore: false,
        programsOrgList: [],
        textButtonSend: "Сохранить",
        loaderSend: false,
        response: {
            status: "",
            info: ""
        },
    }),
    // watch: {
    //     programsOrgList: function (newVal) {
    //         console.log(newVal)
    //         return newVal
    //     }
    // },
    created() {
        if (this.$route.params.id) {
            this.navTitle = "Редактирование организации";
            this.fetchData();
        }
        this.getCities();
    },
    methods: {
        getCities() {
            axios.get('/api/cities')
                .then(response => {
                    this.dataCities = response.data;
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getDataFiles(files) {
            if (files.length > 0) this.dataFiles = files;
        },
        addOrgProgram() {
            this.programsOrgList.push('');
        },
        deleteOrgProgram(index) {
            this.programsOrgList.splice(index, 1);
        },
        saveData() {
            this.loaderSend = true;
            this.response = { status: "", info: "" };

            if (this.$route.params.id) {
                this.updateData();
            } else {
                this.addNewData();
            }
        },
        addNewData() {
            this.loaderSend = true;
            this.response = { status: "", info: "" };

            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('email', this.email);
            formData.append('city', this.city);
            formData.append('description', this.description);
            formData.append('name_short', this.nameShort);
            formData.append('name_full', this.nameFull);
            formData.append('name_filter', this.nameFilter);
            formData.append('num_cert', this.numCert);
            formData.append('date_start', this.dateStart);
            formData.append('date_end', this.dateEnd);
            formData.append('manager', this.manager);
            formData.append('website', this.website);
            formData.append('phone', this.phone);
            formData.append('name', this.name);
            formData.append('address', this.address);
            formData.append('boss', this.boss);
            formData.append('subdiv', this.subdiv);
            this.hiddenProgram ? formData.append('hidden_program', 1) : formData.append('hidden_program', 0);
            this.hiddenReestr ? formData.append('hidden_reestr', 1) : formData.append('hidden_reestr', 0);
            this.hiddenMore ? formData.append('hidden_more', 1) : formData.append('hidden_more', 0);

            if(this.programsOrgList.length > 0) {
                const programsOrg = this.programsOrgList.join(';');
                formData.append('program', programsOrg);
            }

            if (this.dataFiles && this.dataFiles.length > 0) {
                for (let i = 0; i < this.dataFiles.length; i++) {
                    formData.append('dataFiles[]', this.dataFiles[i]);
                }
            }

            axios.post('/api/organizations_store', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                this.response.status = 'success';
                this.response.info = 'Данные успешно сохранены!';

                // Перенаправление на страницу редактирования
                this.$router.push({name: 'OrganizationsEdit', params: {id: response.data.id}});
            })
            .catch(error => {
                this.response.status = 'error';
                if (error.response && error.response.data && error.response.data.errors) {
                    this.response.info = error.response.data.errors.name[0];
                } else {
                    this.response.info = 'Произошла ошибка при сохранении данных.';
                }
            })
            .finally(() => {
                this.loaderSend = false;
            });
        },
        updateData() {
            const id = this.$route.params.id;

            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('email', this.email);
            formData.append('city', this.city);
            formData.append('description', this.description);
            formData.append('name_short', this.nameShort);
            formData.append('name_full', this.nameFull);
            formData.append('name_filter', this.nameFilter);
            formData.append('num_cert', this.numCert);
            formData.append('date_start', this.dateStart);
            formData.append('date_end', this.dateEnd);
            formData.append('manager', this.manager);
            formData.append('website', this.website);
            formData.append('phone', this.phone);
            formData.append('name', this.name);
            formData.append('address', this.address);
            formData.append('boss', this.boss);
            formData.append('subdiv', this.subdiv);
            this.hiddenProgram ? formData.append('hidden_program', 1) : formData.append('hidden_program', 0);
            this.hiddenReestr ? formData.append('hidden_reestr', 1) : formData.append('hidden_reestr', 0);
            this.hiddenMore ? formData.append('hidden_more', 1) : formData.append('hidden_more', 0);

            if(this.programsOrgList.length > 0) {
                const programsOrg = this.programsOrgList.join(';');
                formData.append('program', programsOrg);
            }

            if (this.dataFiles && this.dataFiles.length > 0) {
                for (let i = 0; i < this.dataFiles.length; i++) {
                    formData.append('dataFiles[]', this.dataFiles[i]);
                }
            }

            axios.post(`/api/organizations_update/${id}`, formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                this.response.status = 'success';
                this.response.info = 'Данные успешно обновлены!';
            })
            .catch(error => {
                this.response.status = 'error';
                if (error.response && error.response.data && error.response.data.errors) {
                    this.response.info = error.response.data.errors.name[0];
                } else {
                    this.response.info = 'Произошла ошибка при обновлении данных.';
                }
            })
            .finally(() => {
                this.loaderSend = false;
            });
        },
        fetchData() {
            const id = this.$route.params.id;
            axios.get(`/api/organizations_edit/${id}`)
                .then(response => {
                    if(response.data.org){
                        this.name = response.data.org.name !== '' && response.data.org.name !== null && response.data.org.name !== 'null' ? response.data.org.name : this.name;
                        this.email = response.data.org.email !== '' && response.data.org.email !== null && response.data.org.email !== 'null' ? response.data.org.email : this.email;
                        this.description = response.data.org.description !== '' && response.data.org.description !== null && response.data.org.description !== 'null' ? response.data.org.description : this.description;
                        this.nameShort = response.data.org.name_short !== '' && response.data.org.name_short !== null && response.data.org.name_short !== 'null' ? response.data.org.name_short : this.nameShort;
                        this.nameFull = response.data.org.name_full !== '' && response.data.org.name_full !== null && response.data.org.name_full !== 'null' ? response.data.org.name_full : this.nameFull;
                        this.nameFilter = response.data.org.name_filter !== '' && response.data.org.name_filter !== null && response.data.org.name_filter !== 'null' ? response.data.org.name_filter : this.nameFilter;
                        this.numCert = response.data.org.num_cert !== '' && response.data.org.num_cert !== null && response.data.org.num_cert !== 'null' ? response.data.org.num_cert : this.numCert;
                        this.dateStart = response.data.org.date_start !== '' && response.data.org.date_start !== null && response.data.org.date_start !== 'null' ? response.data.org.date_start : this.dateStart;
                        this.dateEnd = response.data.org.date_end !== '' && response.data.org.date_end !== null && response.data.org.date_end !== 'null' ? response.data.org.date_end : this.dateEnd;
                        this.manager = response.data.org.manager !== '' && response.data.org.manager !== null && response.data.org.manager !== 'null' ? response.data.org.manager : this.manager;
                        this.website = response.data.org.website !== '' && response.data.org.website !== null && response.data.org.website !== 'null' ? response.data.org.website : this.website;
                        this.phone = response.data.org.phone !== '' && response.data.org.phone !== null && response.data.org.phone !== 'null' ? response.data.org.phone : this.phone;
                        this.address = response.data.org.address !== '' && response.data.org.address !== null && response.data.org.address !== 'null' ? response.data.org.address : this.address;
                        this.boss = response.data.org.boss !== '' && response.data.org.boss !== null && response.data.org.boss !== 'null' ? response.data.org.boss : this.boss;
                        this.subdiv = response.data.org.subdiv !== '' && response.data.org.subdiv !== null && response.data.org.subdiv !== 'null' ? response.data.org.subdiv : this.subdiv;
                        this.hiddenProgram = response.data.org.hidden_program !== '' && response.data.org.hidden_program !== null && response.data.org.hidden_program !== 'null' ? response.data.org.hidden_program : this.hiddenProgram;
                        this.hiddenReestr = response.data.org.hidden_reestr !== '' && response.data.org.hidden_reestr !== null && response.data.org.hidden_reestr !== 'null' ? response.data.org.hidden_reestr : this.hiddenReestr;
                        this.hiddenMore = response.data.org.hidden_more !== '' && response.data.org.hidden_more !== null && response.data.org.hidden_more !== 'null' ? response.data.org.hidden_more : this.hiddenMore;
                    }
                    if(response.data.city) {
                        this.city = response.data.city.city_id;
                    }
                })
                .catch(error => {
                    console.error('Ошибка при загрузке данных:', error);
                    this.response.status = 'error';
                    this.response.info = 'Не удалось загрузить данные.';
                });
        },
        returnBack() {
            this.$router.push({ name: 'Organizations' });
        }
    }
}
</script>
