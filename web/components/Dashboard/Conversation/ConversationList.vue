<template>
  <div class="conversations-list grow p-2">
    <header>
      <h1>Messages</h1>
    </header>
    <main>
      <ConversationCard v-for="i in 5" :key="i" />
    </main>
  </div>
</template>

<script setup>
import ConversationCard from './ConversationCard.vue';
import ApiService from '@dayplanner/ApiService'


// fetch conversations from api


</script>
