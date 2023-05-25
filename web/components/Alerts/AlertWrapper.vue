<template>
  <SuccessAlert v-if="status === 'success'">
    {{ msg }}
  </SuccessAlert>

  <ErrorAlert v-if="status === 'error'">
    {{ msg }}
  </ErrorAlert>
</template>

<script setup>

import SuccessAlert from '@/components/Alerts/SuccessAlert.vue'
import ErrorAlert from '@/components/Alerts/ErrorAlert.vue'

const props = defineProps({
  status: {
    type: String,
    default: 'success',
    validator (value) {
      // The value must match one of these strings
      return ['success', 'warning', 'error'].includes(value)
    },
    msg: {
      type: String,
      required: true,
    }
  }
})
</script>
