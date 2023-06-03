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

<template>
  <component :is="messageType" ref="element" :data="message" class="p-3 rounded-lg w-2/3" />
</template>
