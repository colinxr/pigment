<template>
  <div class="conversations-list grow p-2">
    <ConversationCard v-for="i in 5" :key="i" />
  </div>
</template>

<script setup>
import ConversationCard from './ConversationCard.vue';
</script>
