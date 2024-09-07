<script lang="ts" setup>
import { computed, ref } from 'vue'
import DigitalClockDigit from '@/Components/DigitalClockDigit.vue'

// Create reactive date object
const time = ref(new Date())

// Set interval every second
setInterval(() => {
  time.value = new Date()
}, 1000)

const monthNames = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
]

// Computed functions to get units
const getSeconds = computed(() =>
  time.value.getSeconds().toString().padStart(2, '0'),
)
const getMinutes = computed(() =>
  time.value.getMinutes().toString().padStart(2, '0'),
)
const getHours = computed(() =>
  time.value.getHours().toString().padStart(2, '0'),
)
const getDate = computed(() =>
  time.value.getDate().toString().padStart(2, '0') +
  " " +
  monthNames[time.value.getMonth()].toUpperCase()
)
</script>

<template>

<section class="date">
    <div class="clock__col">
      {{ getDate }}
    </div>
</section>
  <section class="clock">
    <div class="clock__col">
      <DigitalClockDigit :value="getHours" />
      <!-- <div class="clock__unit">
        Hours
      </div> -->
    </div>
    <div class="clock__col">
      <DigitalClockDigit :value="getMinutes" />
      <!-- <div class="clock__unit">
        Minutes
      </div> -->
    </div>
    <div class="clock__col">
      <DigitalClockDigit :value="getSeconds" />
      <!-- <div class="clock__unit">
        Seconds
      </div> -->
    </div>
  </section>
</template>

<style>
.clock {
  @apply
    relative grid grid-cols-3 gap-2 bg-gray-900 p-8;
}
.clock__col {
  @apply
    flex flex-col items-center justify-center
    bg-gray-700 border border-gray-600 rounded-xl py-4;
}
.clock__unit {
  @apply
    text-gray-400 uppercase text-xs font-black mt-1;
}

.date {
  @apply
    relative grid grid-cols-1 gap-2 bg-gray-900 p-8 pb-0 text-white;
}

</style>
