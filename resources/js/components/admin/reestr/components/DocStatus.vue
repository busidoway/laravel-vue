<template>
    <div class="doc-status">
        <div class="btn-group">
            <button class="btn btn-outline-gray-300 btn-doc-status dropdown-toggle" type="button" id="doc_status_dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                Статус аттестата
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu py-3" aria-labelledby="doc_status_dropdown">
                <div class="mb-3">
                    <input type="checkbox" id="doc_current" class="form-check-input me-1" v-model="dataStatus" value="1">
                    <label for="doc_current" class="mb-0">Действующий</label>
                </div>
                <div class="mb-3 no-wrap">
                    <input type="checkbox" id="doc_coming" class="form-check-input me-1" v-model="dataStatus" value="2">
                    <label for="doc_coming" class="mb-0">Подходит продление</label>
                </div>
                <div class="">
                    <input type="checkbox" id="doc_expired" class="form-check-input me-1" v-model="dataStatus" value="3">
                    <label for="doc_expired" class="mb-0">Просрочен</label>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => ({
        dataStatus: []
    }),
    props: {
        clear: Boolean
    },
    emits: [
        'status'
    ],
    watch: {
        dataStatus: function (val) {
            this.$emit('status', val);
        },
        clear: function (val) {
            if(val) this.dataStatus = [];
        }
    }
}
</script>

<style>
.doc-status .dropdown-menu {
    min-width: 205px;
}
</style>
