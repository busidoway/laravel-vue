<template>
    <div class="event-sub-categories">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <nav-page :title="navTitle" :nav-list="dataNav" />
            <div class="btn-toolbar mb-2 mb-md-0">
                <RouterLink
                    :to="{ name: 'EventSubCategoriesCreate' }"
                    class="btn btn-sm btn-gray-800 d-inline-flex align-items-center"
                >
                    <svg
                        class="icon icon-xs me-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        >
                        </path>
                    </svg>
                    Добавить
                </RouterLink>
            </div>
        </div>
        <event-sub-categories-list />
    </div>
</template>

<script>
import NavPage from "../../components/NavPage.vue";
import EventSubCategoriesList from "./EventSubCategoriesList.vue";
export default {
    components: {
        EventSubCategoriesList,
        NavPage
    },
    data: () => ({
        navTitle: "Подкатегории мероприятий",
        dataNav: [
            {
                title: "Подкатегории мероприятий"
            }
        ]
    })

}
</script>
