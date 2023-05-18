<template>
  <main class="submission">
    <div v-if="!activeSubmission" class="h-screen flex items-center justify-center">
      Select a conversation or send a new message
    </div>

    <div v-else class="chat-container">
      <MessageContainer v-for="(message, i)  in activeSubmission.messages" :key="i" :message="message" />
    </div>
  </main>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import useDashboardStore from '@/stores/dashboard'
import MessageContainer from './Message/MessageContainer.vue';

const dashboardStore = useDashboardStore()
const { activeSubmission } = storeToRefs(dashboardStore)

// const messages = ref([...activeSubmission.latest_messages])

</script>

<style>
.chat-container {
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
</style>