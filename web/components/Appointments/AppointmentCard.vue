<template>
  <div class="appt-card flex flex-col border-gray-100 border-b">
    <div class=" flex-grow">
      <div class="text-sm font-medium">
        {{ dateTime }}
      </div>
      <div class="text-xs truncate w-40">
        {{ props.appointment.name }}
      </div>
      <div class="text-xs truncate w-40">
        {{ props.appointment.description }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { getReadableDate } from '@/services/dateService'

const props = defineProps({
  appointment: {
    type: Object,
    required: true,
  }
})

const dateTime = computed(() => getReadableDate(props.appointment.startDateTime))

</script>
