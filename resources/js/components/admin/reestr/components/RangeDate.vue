<template>
    <div class="range-date">
        <div class="btn-group">
            <button class="btn btn-outline-gray-300 btn-doc-status dropdown-toggle" type="button" id="range_date_dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                Срок аттестата
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="range_date_dropdown">
                <div class="range-date__dropdown-content">
                    <div class="range-date__item d-flex align-items-center range-date-item">
                        <span class="range-date__item-title">от</span>
                        <div class="input-group">
<!--                            <span class="input-group-text"><i class="fas fa-calendar-week"></i></span>-->
<!--                            <input type="text" id="date_start" class="form-control datepicker-input" placeholder="__.__.____" @click.prevent="getDateStart" autocomplete="off">-->
                            <input type="date" class="form-control range-date-input" placeholder="__.__.____" autocomplete="off" v-model="dateStart">
                        </div>
                    </div>
                    <div class="range-date__item d-flex align-items-center range-date-item">
                        <span class="range-date__item-title">до</span>
                        <div class="input-group">
<!--                            <span class="input-group-text"><i class="fas fa-calendar-week"></i></span>-->
<!--                            <input type="text" id="date_end" class="form-control datepicker-input" placeholder="__.__.____" @click.prevent="getDateEnd" autocomplete="off">-->
                            <input type="date" class="form-control range-date-input" placeholder="__.__.____" autocomplete="off" v-model="dateEnd">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';

export default {
    data: () => ({
        clearDate: false,
        dateStart: "",
        dateEnd: ""
    }),
    props: {
        clear: Boolean
    },
    emits: [
        "date-start-val",
        "date-end-val"
    ],
    watch: {
        dateStart: function (val) {
            val ? this.$emit('date-start-val', val) : this.$emit('date-start-val', '');
        },
        dateEnd: function (val) {
            val ? this.$emit('date-end-val', val) : this.$emit('date-end-val', '');
        },
        clear: function (val) {
            if(val) {
                this.dateStart = "";
                this.dateEnd = "";
                // document.getElementById('date_start').value = "";
                // document.getElementById('date_end').value = "";
            }
        }
    },
    mounted() {
        // this.getDateStart();
        // this.getDateEnd();
    },
    methods: {
        getDateStart() {
            let $this = this;
            new AirDatepicker('#date_start', {
                dateFormat: 'dd.MM.yyyy',
                buttons: ['clear'],
                onSelect(date) {
                    date.formattedDate ? $this.$emit('date-start-val', date.formattedDate) : $this.$emit('date-start-val', '');
                }
            });
        },
        getDateEnd() {
            let $this = this;
            new AirDatepicker('#date_end', {
                dateFormat: 'dd.MM.yyyy',
                buttons: ['clear'],
                onSelect(date) {
                    date.formattedDate ? $this.$emit('date-end-val', date.formattedDate) : $this.$emit('date-end-val', '');
                }
            });
        }
    }
}
</script>

<style>
/*.range-date input[type="date"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
.range-date input[type="date"]::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}*/
.range-date .range-date-input {
    max-width: 124px;
    padding-left: 12px;
    padding-right: 12px;
}
.range-date .input-group-text {
    padding-left: 8px;
    padding-right: 8px;
    font-size: 16px;
}
</style>
