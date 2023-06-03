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
  <!-- -->
  <div class="self-start p-3 rounded-lg">
    <BaseMessage :sender="props.data.sender.full_name" :message="props.data" type="incoming" class="incoming-message" />
  </div>
</template>

<style>
.incoming-message {
  text-align: left;
}
</style>
