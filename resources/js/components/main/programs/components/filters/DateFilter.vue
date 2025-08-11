<template>
    <div class="date-filter programs-main-filters__item">
        <div class="">
            <div class="filter-input-wrap btn btn-filter">
                <input type="text" id="date_filter_input" :class="[shortInp ? 'short-inp' : '', 'filter-input']" :value="titleBtn" readonly>
                <i class="fa-solid fa-calendar-days ms-2 filter-input-icon"></i>
            </div>
        </div>
    </div>
</template>

<script>
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';
export default {
    name: 'DateFilter',
    props: {
        clear: Boolean
    },
    data: () => ({
        datepicker: null,
        titleBtn: "Дата начала",
        shortInp: true,
        isMobile: false
    }),
    watch: {
        clear: function (val) {
            if(!val) {
                this.datepicker = null;
                document.getElementById('date_filter_input').value = "Дата начала";
                this.shortInp = true;
                this.titleBtn = "Дата начала";
                this.initDatepicker();
            }
        }
    },
    created() {
        this.defineUserAgent();
    },
    mounted() {
        this.initDatepicker();
    },
    methods: {
        initDatepicker() {
            this.datepicker = new AirDatepicker('#date_filter_input', {
                dateFormat: 'dd.MM.yyyy',
                buttons: ['clear'],
                inline: false,
                range: true,
                multipleDatesSeparator: ' - ',
                isMobile: this.isMobile,
                autoClose: true,
                onSelect: ({formattedDate}) => {
                    if (formattedDate) {
                        this.$emit('date-start-val', formattedDate);
                        this.shortInp = false;
                    } else {
                        this.$emit('date-start-val', '');
                        this.titleBtn = "Дата начала";
                        this.shortInp = true;
                    }
                },
                onHide: () => {
                    // Проверка на пустое значение при скрытии календаря
                    const value = this.datepicker.$el.value;
                    if (!value || value === '') {
                        this.shortInp = true;
                        this.titleBtn = "Дата начала";
                    }
                }
            });
        },
        toggleDatepicker() {
            if (this.datepicker.visible) {
                this.datepicker.hide();
            } else {
                this.datepicker.show();
            }
        },
        defineUserAgent() {
            const screenWidth = window.screen.width;
            const userAgent = navigator.userAgent.toLowerCase();

            if (userAgent.includes('mobile') && screenWidth < 768) {
                this.isMobile = true;
            }
        }
    }
}
</script>
