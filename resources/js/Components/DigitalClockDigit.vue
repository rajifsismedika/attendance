<script lang="ts" setup>
import { defineProps } from 'vue'

const props = defineProps({
  value: {
    type: String,
    required: true,
  },
})

</script>
<template>
  <div class="clock__digit">
    <Transition mode="out-in" name="change">
      <div :key="props.value.charAt(0)">{{ props.value.charAt(0) }}</div>
    </Transition>
    <Transition mode="out-in" name="change">
      <div :key="props.value.charAt(1)">{{ props.value.charAt(1) }}</div>
    </Transition>
  </div>
</template>
<style scoped>
.clock__digit {
  @apply text-4xl font-black text-gray-100;
}
.clock__digit > div {
  @apply relative inline-block align-top;
}
.change-leave-active,
.change-enter-active {
  transition: all 200ms ease;
}
.change-enter-from {
  transform: translateY(-20px);
  opacity: 0;
}
.change-leave-to {
  transform: translateY(12px);
  opacity: 0;
}
</style>
