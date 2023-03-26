<script setup>
import { watch } from "vue"

const props = defineProps({
  show: Boolean
})

const emit = defineEmits(['close'])

watch(() => props.show, () => {
  if (props.show) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = null;
  }
})
</script>
<template>
  <div 
    v-show="show" 
    @click="emit('close')"
    class="fixed inset-0 bg-black/50 z-10"
  ></div>

  <aside 
    :class="[show ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform border-r border-gray-700"
  >
    <slot />
  </aside>
</template>