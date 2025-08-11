<template>
    <div class="payment-status">
        <div class="">
            <select class="form-select payment-status-select" v-model="statusPayment">
                <option value="0" :selected="clear">Статус оплаты</option>
                <option value="paid">Оплачено</option>
                <option value="none">Не оплачено</option>
            </select>
        </div>
    </div>
</template>
<script>
export default {
    data: () => ({
        statusPayment: "0"
    }),
    props: {
        clear: Boolean
    },
    emits: [
        'status'
    ],
    watch: {
        statusPayment: function (val) {
            this.$emit('status', val);
        },
        clear: function (val) {
            if(val) this.statusPayment = "0";
        }
    }
}
</script>

<style>
.payment-status-select {
    width: 154px;
}
</style>
