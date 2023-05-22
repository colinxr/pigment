<template>
  <component :is="messageType" :data="message" ref="element" />
</template>

<script setup>
import { computed } from 'vue';
import UserMessage from './UserMessage.vue';
import IncomingMessage from './IncomingMessage.vue';

const { message } = defineProps({
  message: {
    type: Object,
    required: true,
  }
})

const messageType = computed(() => {
  if (!message.is_from_admin) return IncomingMessage

  return UserMessage
})
</script>