<template>
  <main class="chat-container flex flex-col h-full w-full bg-white px-4 py-6">

    <div class="h-full overflow-hidden py-4">
      <div class="h-full overflow-y-auto">
        <div class="grid grid-cols-12 gap-y-2" ref="wrapper">
          <div v-if="!activeSubmission" class="flex items-center justify-center">
            Select a conversation or send a new message
          </div>
          <MessageContainer v-else v-for="(message, i)  in messages" :key="i" :message="message" />
        </div>
      </div>
    </div>

    <ConversationTextInput @sendMsg="handleNewMessage" />
  </main>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue';
import { storeToRefs } from 'pinia';

import useDashboardStore from '@/stores/dashboard'
import MessageContainer from './Message/MessageContainer.vue';
import ConversationTextInput from './ConversationTextInput.vue';
import ApiService from '@dayplanner/apiservice';

const dashboardStore = useDashboardStore()

const messages = ref([])
const wrapper = ref(null)

const { activeSubmission } = storeToRefs(dashboardStore)

const buildMessageObject = ({ body, attachments }) => ({
  body,
  attachments,
  status: 'PENDING',
  is_from_admin: true,
})

const handleNewMessage = async (message) => {
  const msgObject = buildMessageObject(message)

  messages.value = [...messages.value, msgObject]

  scrollToLastMessage()

  const messageWasSent = await postMessageToServer(msgObject)

  updateMessage(messageWasSent, message.body)
}


const postMessageToServer = async (message) => {
  const res = await ApiService.messages.post(activeSubmission.value.id, {
    body: message.body,
    files: message.files,
  })

  return res.status === 201
}

const updateMessage = (messageWasSent, bodyText) => {
  const msg = messages.value.find(({ body, status }) => status && body === bodyText)

  msg.status = !messageWasSent ? 'FAILED' : null
}

const scrollToLastMessage = () => {
  nextTick(() => wrapper.value.scrollIntoView({ block: 'end' }))
}

watchEffect(() => {
  if (!activeSubmission.value) return

  messages.value = activeSubmission.value.messages

  scrollToLastMessage()
})

</script>

<style>
.chat-container {
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
</style>