<template>
    <div class="date-list">
        <div class="mb-2 mx-0 row">
            <div class="d-flex">
                <label class="form-check-label" for="date_public">Дата</label>
            </div>
        </div>
        <div class="mb-2 mx-0 row">
            <div class="">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input form-check-green-input" type="checkbox" name="check_date" id="check_date" :checked="dateless" @change="checkDateless($event)" >
                    <label class="form-check-label" for="check_date">Бессрочно</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input form-check-green-input" type="checkbox" name="check_range_date" id="check_range_date" :checked="range" @change="checkRange($event)">
                    <label class="form-check-label" for="check_range_date">Включить диапазон: от и до</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input form-check-green-input" type="checkbox" name="check_multi_date" id="check_multi_date" :checked="multiple" @change="checkMultiple($event)" >
                    <label class="form-check-label" for="check_multi_date">Включить выбор нескольких дат</label>
                </div>
            </div>
        </div>
        <div class="row mb-4 mx-0">
            <div class="col-md-6">
                <div class="input-group px-0">
                    <input type="text" name="date_public" id="date_public" class="form-control date-inp" autocomplete="off" placeholder="Дата" :disabled="dateless" :value="date_public" required>
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
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
    // props:['datePublic', 'dateEnd', 'dateList', 'dateTermless'],
    props: {
        eventId: Number
    },
    data: () => ({
        multiple: false,
        range: false,
        dateless: false,
        datePublic: "",
        dateEnd: "",
        dateList: "",
        dateTermless: false,
        date_public: "",
        date_start: "",
        date_end: ""
    }),
    mounted() {
        this.dateData();
        this.setMainDatepicker(this.range, this.multiple);
    },
    methods: {
        dateData() {
            if(this.eventId){
                axios.get('/api/event_date/'+this.eventId)
                .then(res => {
                    // console.log(res);
                    let date_public_format = res.data.event_date.date_public_format,
                        e_date_public = res.data.event_date.date_public,
                        date_end_format = res.data.event_date.date_end_format,
                        e_date_end = res.data.event_date.date_end,
                        e_date_list = res.data.event_date.date_list,
                        date_list_arr = res.data.date_list;

                    if(date_public_format){
                        this.date_public = date_public_format;
                        if(date_end_format && !e_date_list){
                            this.date_public = date_public_format + ', ' + date_end_format;
                            this.range = true;
                            this.setMainDatepicker(true, false, e_date_public, e_date_end);
                        }else{
                            this.setMainDatepicker(false, false, e_date_public);
                        }
                    }

                    if(e_date_list){
                        this.date_public = e_date_list;
                        this.multiple = true;
                        this.setMainDatepicker(false, true, null, null, date_list_arr);
                    }

                    if(!e_date_public && !e_date_end && !e_date_list){
                        this.dateTermless = true;
                    }

                }).catch(err => {

                })
            }
        },
        setMainDatepicker(range, multiple, date_start = null, date_end = null, date_list = null){
            if(date_list){
                new AirDatepicker('#date_public', {
                    timepicker: true,
                    range: range,
                    multipleDates: multiple,
                    buttons: ['clear'],
                    selectedDates: date_list
                });
            }else if(date_start && date_end){
                new AirDatepicker('#date_public', {
                    timepicker: true,
                    range: range,
                    multipleDates: multiple,
                    buttons: ['clear'],
                    selectedDates: [date_start, date_end]
                });
            }else if(date_start){
                new AirDatepicker('#date_public', {
                    timepicker: true,
                    range: range,
                    multipleDates: multiple,
                    buttons: ['clear'],
                    selectedDates: date_start
                });
            }else{
                new AirDatepicker('#date_public', {
                    timepicker: true,
                    range: range,
                    multipleDates: multiple,
                    buttons: ['clear']
                });
            }
        },
        checkRange(event){
            this.dateless = false;
            this.multiple = false;
            this.range = event.target.checked;
            this.setMainDatepicker(this.range);
        },
        checkMultiple(event){
            this.range = false;
            this.dateless = false;
            this.multiple = event.target.checked;
            this.setMainDatepicker(this.range, this.multiple);
        },
        checkDateless(event){
            this.range = false;
            this.multiple = false;
            this.dateless = event.target.checked;
        }
    }
}
</script>
