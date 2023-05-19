<template>
  <main class="chat-container flex flex-col h-full w-full bg-white px-4 py-6">

    <div class="h-full overflow-hidden py-4">
      <div class="h-full overflow-y-auto">
        <div class="grid grid-cols-12 gap-y-2">
          <div v-if="!activeSubmission" class="flex items-center justify-center">
            Select a conversation or send a new message
          </div>
          <MessageContainer v-else v-for="(message, i)  in activeSubmission.messages" :key="i" :message="message" />
        </div>
      </div>
    </div>

    <ConversationTextInput />
  </main>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import useDashboardStore from '@/stores/dashboard'
import MessageContainer from './Message/MessageContainer.vue';
import ConversationTextInput from './ConversationTextInput.vue';

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