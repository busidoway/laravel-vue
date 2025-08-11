<template>
    <div class="user-video">
        <div class="mb-3 d-flex row">
            <div class="fw-bolder col-6"><label class="m-0">Ведущие</label></div>
            <div class="d-flex align-items-end col-6">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input form-check-green-input" name="position_visible" id="position_visible" v-model="posVisible">
                    <label for="position_visible">Отобразить должность</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <select size="6" class="form-select" name="persons[]" v-model="select.val" multiple aria-label="multiple select">
                    <option :value="{value: person.id, last_name: person.last_name, name: person.name, middle_name: person.middle_name}" v-for="person in persons" :key="person.id">{{ person.last_name }} {{ person.name }} {{ person.middle_name }}</option>
                </select>
                <div class="mb-2">
                    <button class="btn btn-primary" @click.prevent="addPerson">Выбрать</button>
                </div>
            </div>
            <div class="mb-3 fs-6 col-6">
                <div class="user-video__list">
                    <div class="mb-1" v-for="item in eventPerson" :key="item.id">
                        <input type="checkbox" name="user_check" :id="'check' + item.id" v-model="checkName" :value="{id: item.id, last_name: item.last_name, name: item.name, middle_name: item.middle_name}" @change="toggleBtnRemove" class="form-check-input me-2">
                        <label :for="'check' + item.id" class="form-check-label">{{ item.last_name }} {{ item.name }} {{ item.middle_name }}</label>
                        <input type="hidden" :name="'event_person[]'" :value="item.id">
                        <!-- <input type="hidden" :name="'event_person['+item.id+']'" :value="item.uv_id"> -->
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2" style="height:24px;">
                    <a class="text-danger" @click.prevent="removePerson" v-if="btnRemove.active"><i class="fas fa-trash-alt me-2"></i>Удалить отмеченные</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        id: {
            type: Number,
            default:0
        }
    },
    data: () => ({
        select: {
            val: []
        },
        persons:[],
        eventPerson: [],
        btnRemove: {
            active: false
        },
        checkName: [],
        posVisible: false
    }),
    mounted() {
        this.loadPersons(this.id);
    },
    methods: {
        loadPersons(id){
            if(id == 0) {
                axios.get('/api/event_person/persons')
                .then(res => {
                    // console.log(res);
                    this.persons = res.data.persons;
                }).catch(err => {
                    console.log(err);
                })
            }else{
                axios.get('/api/event_person/' + id)
                .then(res => {
                    // console.log(res);
                    this.persons = res.data.persons;
                    this.eventPerson = res.data.event_person;
                    if(res.data.event.position_visible == 1){
                        this.posVisible = true;
                    }else{
                        this.posVisible = false;
                    }
                    // this.posVisible = res.data.event.position_visible;

                    // console.log('posVisible:',this.posVisible);
                }).catch(err => {
                    console.log(err);
                })
            }
        },
        addPerson(){
            if(this.persons.length != 0){
                var arr_select = this.select.val;

                for(let i in arr_select){
                    let person_id = arr_select[i].value;
                    this.eventPerson.push({
                        id: arr_select[i].value,
                        last_name: arr_select[i].last_name,
                        name: arr_select[i].name,
                        middle_name: arr_select[i].middle_name
                    });
                    for(let n in this.persons){
                        if(this.persons[n].id == person_id) this.persons.splice(n, 1);
                    }
                }
            }
        },
        toggleBtnRemove(){
            if(this.checkName.length != 0) 
                this.btnRemove.active = true
            else
                this.btnRemove.active = false
        },
        removePerson(){
            if(this.eventPerson.length != 0){
                for(let i in this.checkName){
                    let person_id = this.checkName[i].id;
                    this.persons.push(this.checkName[i]);
                    for(let n in this.eventPerson){
                        if(this.eventPerson[n].id == person_id) this.eventPerson.splice(n, 1);
                    }
                }
                this.checkName = [];
                this.btnRemove.active = false
            }
        }
    }
}
</script>
