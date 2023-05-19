<template>
  <div class="msg flex flex-col">
    <div class="msg__body relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
      <div>{{ props.message.body }}</div>
      <div class="timestamp">
        {{ props.message.timestamp }}
      </div>
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
.incoming .msg__name {
  @apply justify-start;
}

.user .msg__name {
  @apply justify-end;
}

.user .msg__body {
  @apply text-left;
}
</style>