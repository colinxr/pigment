<template>
  <main class="chat-container h-full overflow-hidden py-4">
    <div class="h-full overflow-y-autoo">
      <div class="grid grid-cols-12 gap-y-2">
        <div v-if="!activeSubmission" class="h-screen flex items-center justify-center">
          Select a conversation or send a new message
        </div>
        <MessageContainer v-else v-for="(message, i)  in activeSubmission.messages" :key="i" :message="message" />
      </div>
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