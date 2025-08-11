<template>
    <div class="app-search">
        <div class="input-group fmxw-400">
            <span class="input-group-text">
                <svg class="icon icon-xs" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </span>
            <input type="text" class="form-control" placeholder="Поиск" v-model="searchReq">
        </div>
    </div>
</template>
<script>
export default {
    data: () => ({
        searchReq: ""
    }),
    props: {
        clear: Boolean
    },
    emits: [
        "search-req"
    ],
    watch: {
        searchReq: function (val) {
            this.$emit('search-req', val);
        },
        clear: function (val) {
            if(val) this.searchReq = "";
        }
    }
}
</script>
