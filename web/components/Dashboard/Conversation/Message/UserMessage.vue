<template>
  <BaseMessage sender="you" :message="props.data" class="user-message" />
</template>

<script setup>

import BaseMessage from './BaseMessage.vue';

const props = defineProps({
  data: {
    type: Object,
    required: true,
  }
})
</script>

<style>
.user-message {
  text-align: right;
}
</style>