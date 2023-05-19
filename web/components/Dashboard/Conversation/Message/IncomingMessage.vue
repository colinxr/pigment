<template>
  <div class="col-start-1 col-end-8 p-3 rounded-lg">
    <BaseMessage :sender="props.data.sender.full_name" :message="props.data" class="incoming-message" />
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
.incoming-message {
  text-align: left;
}
</style>