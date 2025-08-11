<template>
    <nav aria-label="Page navigation example">
        <ul class="pagination mb-0">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <a class="page-link" href="javascript:;" @click.prevent="changePage(currentPage - 1)">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
            <li class="page-item" :class="{ active: currentPage === 1 }">
                <a class="page-link" href="javascript:;" @click.prevent="changePage(1)">1</a>
            </li>
            <li v-if="startEllipsis" class="page-item disabled"><span class="page-link">...</span></li>
            <li class="page-item" v-for="page in visiblePages" :key="page" :class="{ active: currentPage === page }">
                <a class="page-link" href="javascript:;" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li v-if="endEllipsis" class="page-item disabled"><span class="page-link">...</span></li>
            <li class="page-item" :class="{ active: currentPage === totalPages }" v-if="totalPages > 1">
                <a class="page-link" href="javascript:;" @click.prevent="changePage(totalPages)">{{ totalPages }}</a>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <a class="page-link" href="javascript:;" @click.prevent="changePage(currentPage + 1)">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "PaginationNav",
    props: {
        total: Number,
        item: Number,
        activePage: Number
    },
    emits: [
        'page-changed'
    ],
    data() {
        return {
            currentPage: 1,
            maxVisiblePages: 7,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.total / this.item);
        },
        visiblePages() {
            const pages = [];

            if(this.activePage) this.currentPage = this.activePage;

            const halfRange = Math.floor((this.maxVisiblePages - 3) / 2);
            const startPage = Math.max(2, this.currentPage - halfRange);
            const endPage = Math.min(this.totalPages - 1, this.currentPage + halfRange);

            if (this.totalPages <= this.maxVisiblePages) {
                for (let i = 2; i < this.totalPages; i++) {
                    pages.push(i);
                }
            } else {
                if (startPage > 2) {
                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }
                } else {
                    for (let i = 2; i < this.maxVisiblePages; i++) {
                        pages.push(i);
                    }
                }
            }

            return pages;
        },
        startEllipsis() {
            return this.totalPages > this.maxVisiblePages && this.currentPage > Math.floor((this.maxVisiblePages - 3) / 2) + 2;
        },
        endEllipsis() {
            return this.totalPages > this.maxVisiblePages && this.currentPage < this.totalPages - Math.floor((this.maxVisiblePages - 3) / 2) - 1;
        },
    },
    methods: {
        changePage(pageNumber) {
            if (pageNumber > 0 && pageNumber <= this.totalPages) {
                this.currentPage = pageNumber;
                this.$emit("page-changed", pageNumber);
            }
        },
    },
};
</script>
