<template>
  <component :is="AlertToRender">
    {{ msg }}
  </component>
</template>

<script setup>

import SuccessAlert from '@/components/Alerts/SuccessAlert.vue'
import ErrorAlert from '@/components/Alerts/ErrorAlert.vue'

const props = defineProps({
  status: {
    type: String,
    default: '',
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

const AlertToRender = computed(() => {
  if (props.status === 'success') { return SuccessAlert }

  if (props.status === 'error') { return ErrorAlert }
})
</script>
