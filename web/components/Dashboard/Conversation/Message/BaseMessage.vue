<template>
  <div class="msg flex flex-col" :class="props.type === 'user' ? 'user' : 'incoming'">

    <div class="text-sm font-medium px-2" :class="props.type === 'user' ? 'self-end' : 'justify-start'">
      {{ props.sender }}
    </div>
    <div class="msg__body relative fle mt-1 text-sm py-2 px-4 shadow rounded-xl"
      :class="props.type === 'user' ? 'bg-indigo-100 text-left' : 'bg-white'">
      <div>{{ props.message.body }}</div>
      <div class="timestamp">
        {{ props.message.timestamp }}
      </div>
    </div>
    <span class="mt-1 pr-2 text-xs self-end">{{ props.message.status }}</span>
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
  },
  type: {
    type: String,
    default: 'user'
  }

})
</script>