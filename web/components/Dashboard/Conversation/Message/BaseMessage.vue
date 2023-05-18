<template>
  <div class="message">
    <span class="sender">{{ props.sender }}</span>
    <div class="content">
      {{ props.message.body }}
    </div>
    <div class="timestamp">
      {{ props.message.timestamp }}
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  message: {
    type: Object,
    required: true,
  },
  sender: {
    type: String,
    required: true,
  }

})
</script>

<style>
.message {
  margin-bottom: 10px;
}

.message .sender {
  font-weight: bold;
  color: blue;
}

.message .content {
  margin-left: 10px;
}

.message .timestamp {
  font-size: 0.8em;
  color: #999;
}
</style>