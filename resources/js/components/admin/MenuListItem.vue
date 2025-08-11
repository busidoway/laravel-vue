<template>
    <!-- <draggable class="menu-list__content" v-model="model" group="people" @start="drag=true" @end="drag=false"> -->
        <div class="">
            <div class="menu-list__item__wrap">
                <div class="menu-list__item__drag-btn drag-trigger" draggable @drag="dragHandler" :class="{'grabbing': grabbingCursor}" @mousedown="toggleGrabCursor(true)" @mouseup="toggleGrabCursor(false)">
                    <i class="fas fa-arrows-alt"></i>
                </div>
                <div class="menu-list__item__content">
                    <div class="menu-list__item__title">{{ model.title }}</div>
                    <div class="menu-list__item__action">
                        <div class="btn-group">
                            <form action="" method="post">
                                <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="icon icon-sm">
                                        <span class="fas fa-ellipsis-h icon-dark"></span>
                                    </span>
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu py-0">
                                    <a class="dropdown-item" href=""><span class="fas fa-edit me-2"></span>Редактировать</a>
                                    <button type="submit" class="dropdown-item text-danger rounded-bottom"><span class="fas fa-trash-alt me-2"></span>Удалить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="menu-list__item__settings">
                        <span class="menu-list__item__settings__btn">
                            <i class="fas fa-cog"></i>
                        </span>
                    </div>
                </div>
                <div class="menu-list__item__toggle-btn">
                    <i class="fas fa-caret-right"></i>
                </div>
            </div>
            <!-- <div v-if="item.children">
            <MenuListItem class="menu-list__item ms-3"  :model="node.children"></MenuListItem>
            </div> -->
        </div>
    <!-- </draggable> -->
</template>

<script>
// import 'he-tree-vue/dist/he-tree-vue.css';
// import {Tree, Draggable} from 'he-tree-vue';

export default {
    name: 'MenuListItem',
    // extends: Tree,
    props: {
        // value: Array,
        model: Object,
        // triggerClass: {default: 'drag-trigger'},
        // draggable: {type: [Boolean, Function], default: false}
    },
    data: () => ({
        grabbingCursor: false
    }),
    mounted() {
        // console.log(Tree);
    },
    methods: {
        toggleGrabCursor(val){
            if(val) 
                this.grabbingCursor = true;
            else    
                this.grabbingCursor = false;
            // console.log('123');
        },
        dragHandler(){
            console.log('fdfd');
        }
    }
}
</script>