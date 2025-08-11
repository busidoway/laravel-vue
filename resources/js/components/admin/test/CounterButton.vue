<template>
    <div class="">
        <button class="btn btn-primary" @click="increment">Click +1</button>
        <span class="ms-2">{{ count }}</span>
    </div>
</template>

<script lang="ts">
import { ref, onMounted, defineComponent } from '@vue/composition-api';
export default defineComponent( {
    setup() {
        const count = ref<number>(0);

        function increment(): void {
            count.value++;
        }

        onMounted(() => {
            console.log('Component mounted.')
        })

        return {
            count,
            increment
        }
    }
})
</script>
