<template>
    <div class="member-status">
        <div class="">
            <select class="form-select member-status-select" v-model="statusMember">
                <option value="0" :selected="clear">Статус членства</option>
                <option value="membership">Членство</option>
                <option value="reestr">Запись в реестре</option>
            </select>
        </div>
    </div>
</template>
<script>
export default {
    data: () => ({
        statusMember: "0"
    }),
    props: {
        clear: Boolean
    },
    emits: [
        'status'
    ],
    watch: {
        statusMember: function (val) {
            this.$emit('status', val);
        },
        clear: function (val) {
            if(val) this.statusMember = "0";
        }
    }
}
</script>

<style>
.member-status-select {
    width: 174px;
}
</style>
