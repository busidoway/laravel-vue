<template>
    <div class="duplicate-filter">
        <input type="checkbox" class="form-check-input" id="duplicate_filter" v-model="checkInp">
        <label for="duplicate_filter" class="ms-2">отобразить дубликаты</label>
    </div>
</template>

<script>
export default {
    data: () => ({
        checkInp: false
    }),
    emits: [
        "check-input"
    ],
    watch: {
         checkInp: function (val) {
             this.$emit('check-input', val);
         }
    },
    methods: {

    }
}
</script>
