<template>
    <div class="card card-body border-0 shadow">
        <div>
            <div class="drop-zone" @drop="onDrop($event, 1)" @dragover.prevent @dragenter.prevent>
                <div
                    class="drag-el"
                    v-for="item in listOne"
                    :key="item.title"
                    draggable
                    @dragstart="startDrag($event, item)"
                    >
                    {{ item.title }}
                </div>
            </div>
            <div class="drop-zone" @drop="onDrop($event, 2)" @dragover.prevent @dragenter.prevent>
                <div
                    class="drag-el"
                    v-for="item in listTwo"
                    :key="item.title"
                    draggable
                    @dragstart="startDrag($event, item)"
                    >
                    {{ item.title }}
                </div>
            </div>
        </div>

        <div class="menu-list">
            <div class="row">
                <div class="col-6">
                    <Tree @drag="dragHandler" :value="menuList">
                        <div slot-scope="{node}">
                            <MenuListItem :model="node"></MenuListItem>
                        </div>
                    </Tree>
                    <!-- <MenuListItem :value="menuList" draggable ></MenuListItem> -->
                </div>
                <div class="col-6"></div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import MenuListItem from './MenuListItem.vue';
// import Sortable from 'sortablejs';
// import draggable from 'vuedraggable';
import 'he-tree-vue/dist/he-tree-vue.css';
import {Tree, Draggable} from 'he-tree-vue';

export default {
    components: {
        MenuListItem,
        // draggable
        Tree: Tree.mixPlugins([Draggable])
    },
    data: () => ({
        menuList: [],
        // menu
        // grabbingCursor: false
        // treeData: [{id:1, text: 'node 1'}, {id:2, text: 'node 2', children: [{id:3, text: 'node 2-1'}]}]
        // treeData: []
        items: [
            {
            id: 0,
            title: 'Item A',
            list: 1,
            },
            {
            id: 1,
            title: 'Item B',
            list: 1,
            },
            {
            id: 2,
            title: 'Item C',
            list: 2,
            },
        ],
    }),
    computed: {
        listOne() {
            return this.items.filter((item) => item.list === 1)
        },
        listTwo() {
            return this.items.filter((item) => item.list === 2)
        },
    },
    mounted() {
        // this.loadMenu();
        // console.log(this.treeData);
    },
    methods: {
        startDrag(evt, item) {
            evt.dataTransfer.dropEffect = 'move'
            evt.dataTransfer.effectAllowed = 'move'
            evt.dataTransfer.setData('itemID', item.id)
        },
        onDrop(evt, list) {
            const itemID = evt.dataTransfer.getData('itemID')
            const item = this.items.find((item) => item.id == itemID)
            item.list = list
        },
        loadMenu(){
            axios.get('/api/menu/list')
            .then(res => {
                // console.log(res.data.menu);
                var menu = res.data.menu;
                // this.treeData = res.data.menu;
                for (let i in menu) {
                    this.menuList.push({
                        id: menu[i].id,
                        title: menu[i].title,
                        children: menu[i].children
                        // children: [{
                        //     id: menu[i].children.id,
                        //     text: menu[i].children.title,
                        // }]
                    });
                }
                // console.log(this.menuList);
            }).catch(err => {
                console.log(err);
            })
        },
        dragHandler(model, component, e) {
            // console.log('dragHandler: ', model);
            // console.log('dragNode: ', model.dragNode);
            // console.log(this.menuList);
        },
        // toggleGrabCursor(val){
        //     if(val) 
        //         this.grabbingCursor = true;
        //     else    
        //         this.grabbingCursor = false;
        //     // console.log('123');
        // }
    }
}
</script>

<style scoped>
.drop-zone {
  background-color: #eee;
  margin-bottom: 10px;
  padding: 10px;
}
.drag-el {
  background-color: #fff;
  margin-bottom: 10px;
  padding: 5px;
}
</style>
