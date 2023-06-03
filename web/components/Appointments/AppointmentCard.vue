<script setup>
import { computed } from 'vue'
import { getReadableDate } from '@/services/dateService'

const props = defineProps({
  appointment: {
    type: Object,
    required: true,
  },
  status: {
    type: String,
    default: 'active'
  }
})

const dateTime = computed(() => getReadableDate(props.appointment.startDateTime))

</script>

<template>
  <div class="appt-card">
    <div class="text-sm font-medium" :class="{ 'line-through ': props.status !== 'active' }">
      {{ dateTime }}
    </div>
    <div class="text-xs truncate w-40">
      {{ props.appointment.name }}
    </div>
    <div class="text-xs truncate w-40">
      {{ props.appointment.description }}
    </div>
  </div>
</template>
