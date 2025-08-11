<template>
    <nav aria-label="Page navigation example">
        <ul class="pagination mb-0">
            <li class="page-item">
                <a class="page-link" href="javascript:;" @click.prevent="changePage(currentPage - 1)"><i class="fas fa-angle-left"></i></a>
            </li>
            <li class="page-item" :class="{active: currentPage == p}" v-for="(p, index) in totalPages" :key="index">
                <a class="page-link" href="javascript:;" v-if="p === 0">...</a>
                <a class="page-link" href="javascript:;" @click.prevent="changePage(p)" v-else>{{ p }}</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="javascript:;" @click.prevent="changePage(currentPage + 1)"><i class="fas fa-angle-right"></i></a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: "Pagination",
        props: {
            total: Number,
            item: Number
        },
        data() {
            return {
                currentPage: 1,
                maxCountItems: 8,
                totalCount: 0,
                lastPage: null
            }
        },
        mounted() {
            this.totalCount = Math.ceil(this.total / this.item);
            this.getCurrPage()
            this.getPageList()
        },
        computed: {
            totalPages() {
                return this.getPageList()
            }
        },
        methods: {
            changePage(pageNumber) {
                if(pageNumber > 0 && pageNumber <= this.totalCount){
                    this.currentPage = pageNumber
                    this.$emit('page-changed', pageNumber)
                }
            },
            getCurrPage() {
                let page = parseInt(this.$route.params.page);
                if(page) this.currentPage = page;
            },
            getPageList() {
                let pagesList = [];
                let countPages = Math.ceil(this.total / this.item);
                let halfMaxCount = Math.ceil(this.maxCountItems / 2);
                let page = parseInt(this.$route.params.page);
                let lastPage = countPages - halfMaxCount;
                let startPage = halfMaxCount;
                this.totalCount = countPages;
                if(page <= halfMaxCount){
                    lastPage = countPages - Math.ceil(halfMaxCount / 2);
                }else if(page > halfMaxCount){
                    startPage = Math.ceil(halfMaxCount / 2);
                }
                if(page > halfMaxCount && page <= lastPage) {
                    lastPage = countPages - Math.ceil(halfMaxCount / 2);
                }
                if(countPages > this.maxCountItems){
                    for(let i = 1; i <= countPages; i++){
                        if(i <= startPage){
                            pagesList.push(i);
                        }
                        if(i > halfMaxCount && i < (halfMaxCount + 2) && i < lastPage){
                            pagesList.push(0);
                        }
                        if(page > halfMaxCount && page <= lastPage && i === page){
                            pagesList.push(i);
                        }
                        if(i > page && i < (page + 2) && i < lastPage && page > halfMaxCount) {
                            pagesList.push(0);
                        }
                        if(i > lastPage){
                            pagesList.push(i);
                        }
                    }
                }else{
                    for(let i = 1; i <= countPages; i++){
                        pagesList.push(i);
                    }
                }

                return pagesList;
            }

        }
    }
</script>
