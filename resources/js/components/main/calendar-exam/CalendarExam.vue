<template>
    <div class="calendar-exam">
        <vc-calendar
            class="custom-calendar d-none d-md-block"
            :masks="masks"
            :attributes="dataArr"
            :min-date="startDate"
            :max-date="endDate"
            :from-page="calendarStartMonth"
            disable-page-swipe
            is-expanded
        >
        <template v-slot:day-content="{ day, attributes }">
            <div class="flex flex-col h-full z-10 overflow-hidden">
                <span :class="['day-label', 'text-sm', 'text-gray-900', day.isDisabled ? 'is-disabled' : '']">{{ day.day }}</span>
                <div class="d-flex justify-content-center align-items-center flex-grow overflow-y-auto overflow-x-auto">
                    <button
                        v-for="attr in attributes"
                        :key="attr.key"
                        class="btn btn-primary btn-modal"
                        :data-date="attr.customData.title"
                        :data-modal-title="attr.customData.title"
                        data-recaptcha-id="recaptcha_exam"
                        data-bs-toggle="modal"
                        data-bs-target="#formExamModal"
                    >
                        Записаться
                    </button>
                </div>
            </div>
        </template>
        </vc-calendar>
        <div class="d-md-none d-block mb-3">Нажмите на дату, чтобы записаться на экзамен.</div>
        <div class="d-flex flex-column align-items-center d-md-none d-block">
            <vc-calendar
                :masks="masks"
                :attributes="dataArr"
                :min-date="startDate"
                :max-date="endDate"
                :from-page="calendarStartMonth"
            >
                <template v-slot:day-content="{ day, attributes }">
                    <span
                        tabindex="-1"
                        aria-label=""
                        :aria-disabled="day.isDisabled ? 'true' : 'false'"
                        role="button"
                        :class="['vc-day-content', 'vc-focusable', day.isDisabled ? 'is-disabled' : '']"
                    >
                        {{ day.day }}
                    </span>
                    <div class="vc-button-content" v-for="attr in attributes" :key="attr.key">
                        <button
                            class="vc-highlight vc-button btn btn-modal"
                            :data-date="attr.customData.title"
                            :data-modal-title="attr.customData.title"
                            data-recaptcha-id="recaptcha_exam"
                            data-bs-toggle="modal"
                            data-bs-target="#formExamModal"
                        >
                            {{ day.day }}
                        </button>
                    </div>
                </template>
            </vc-calendar>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            masks: {
                weekdays: 'WWW',
            },
            dataArr: [],
            startDate: "",
            endDate: "",
            calendarStartMonth: null,
            loading: true,
            status: {
                success: false,
                error: false,
                info: ""
            },
        };
    },
    mounted() {
        this.getDataList();
    },
    methods: {
        getDataList() {
            axios.get('/api/exams')
                .then(response => {
                    this.status.error = false;
                    this.status.success = true;
                    this.dataArr = [];
                    const exams = response.data.exams;

                    this.dataArr = exams.map((exam, index) => {
                        const [year, month, day] = exam.date_init.split('-').map(Number);
                        const date = new Date(year, month - 1, day);
                        return {
                            key: index + 1,
                            customData: {
                                title: exam.name
                            },
                            dates: date
                        };
                    });

                    this.startDate = response.data.start_date.date;
                    this.endDate = response.data.end_date;

                    // Получаем первую дату из dataArr и устанавливаем её как начальный месяц
                    if (this.dataArr.length > 0) {
                        const firstDate = this.dataArr[0].dates;
                        this.calendarStartMonth = { year: firstDate.getFullYear(), month: firstDate.getMonth() + 1 }; // Формируем объект
                    }

                })
                .catch(error => {
                    this.status.success = false;
                    this.status.error = true;
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.response.info = error.response.data.errors.name[0];
                    } else {
                        this.response.info = 'Произошла ошибка при загрузке данных.';
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }
}
</script>

<style scoped>
::-webkit-scrollbar {
    width: 0px;
}

::-webkit-scrollbar-track {
    display: none;
}
</style>
