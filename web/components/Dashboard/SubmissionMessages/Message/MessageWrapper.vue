<template>
  <component :is="messageType" ref="element" :data="message" />
</template>

<script setup>
import { computed } from 'vue'
import UserMessage from './UserMessage.vue'
import IncomingMessage from './IncomingMessage.vue'

const props = defineProps({
  message: {
    type: Object,
    required: true,
  }
})

const messageType = computed(() => {
  if (!props.message.is_from_admin) return IncomingMessage

  return UserMessage
})
</script>
