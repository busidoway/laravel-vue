<template>
    <div class="programs-info-section" v-if="dataUrl">
        <div class="programs-info-item">
            <div class="programs-info-text" v-if="dataText">
                {{ dataText }}
            </div>
            <div class="programs-info-btn">
                <a :href="dataUrl" class="btn btn-white">Подробнее</a>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        typeProgram: Number
    },
    data: () => ({
        dataUrl: "",
        dataText: ""
    }),
    watch: {
        typeProgram(newVal) {
            this.getData();
        }
    },
    created() {
        this.getData();
    },
    methods: {
        getData() {
            if(this.typeProgram) {
                axios.get(`/api/type_program_edit/${this.typeProgram}`)
                    .then(response => {
                        console.log(response)
                        this.dataUrl = response.data.url;
                        this.dataText = response.data.text;
                    })
                    .catch(error => {
                        console.error('Ошибка при загрузке данных:', error);
                    });
            }
        }
    }
}
</script>
