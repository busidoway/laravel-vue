<template>
    <div class="modal fade" id="modal-delete-list" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">{{ title }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" @click.prevent="deleteConfirm()">Удалить</button>
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        title: String
    },
    data: () => ({

    }),
    methods: {
        deleteConfirm(){
            this.$emit('delete-confirm')
        }
    }
}
</script>