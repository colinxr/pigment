<template>
  <div class="col-start-6 col-end-13 p-3 rounded-lg">
    <BaseMessage sender="you" :message="props.data" class="user" />
  </div>
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