<script setup>

import BaseMessage from './BaseMessage.vue'

const props = defineProps({
  data: {
    type: Object,
    required: true,
  }
})
</script>

<template>
  <!--  -->
  <div class="self-end">
    <BaseMessage sender="you" :message="props.data" class="user" />
  </div>
</template>

<style>
.user-message {
  text-align: right;
}
</style>
