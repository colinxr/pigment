<template>
  <div class="badge badge-accent">{{ props.status }}</div>
</template>

<script setup>
const props = defineProps({
  status: {
    type: String,
    required: true,
  }
})
</script>