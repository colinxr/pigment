<script setup>
import { ref, nextTick } from 'vue'
import { storeToRefs } from 'pinia'

import ApiService from '@dayplanner/apiservice'
import useDashboardStore from '@/stores/dashboard'
import ActionPane from '@/components/ActionPane/ActionPane.vue'
import MessageWrapper from '@/components/Dashboard/SubmissionMessages/Message/MessageWrapper.vue'
import ConversationTextInput from '@/components/Dashboard/Conversation/ConversationTextInput.vue'
import ConversationHeader from '@/components/Dashboard/Conversation/ConversationHeader.vue'

const dashboardStore = useDashboardStore()

const messages = ref([])
const wrapper = ref(null)

const { updateSubmissionsListOrder } = dashboardStore
const { activeSubmission } = storeToRefs(dashboardStore)

const buildMessageObject = ({ body, attachments }) => ({
	body,
	attachments,
	status: 'PENDING',
	is_from_admin: true,
})

const handleNewMessage = async message => {
	const msgObject = buildMessageObject(message)

	messages.value = [...messages.value, msgObject]

	scrollToLastMessage()

	const res = await postMessageToServer(msgObject)

	const messageWasSent = res.status === 201

	updateMessage(messageWasSent, message.body)

	if (messageWasSent) {
		activeSubmission.value.last_message = res.data.data
		updateSubmissionsListOrder(activeSubmission.value.id)
	}
}

const postMessageToServer = async message => {
	const res = await ApiService.messages.post(activeSubmission.value.id, {
		body: message.body,
		files: message.files,
	})

	return res

	return res.status === 201
}

const updateMessage = (messageWasSent, bodyText) => {
	const msg = messages.value.find(
		({ body, status }) => status && body === bodyText
	)

	msg.status = !messageWasSent ? 'FAILED' : null
}

const scrollToLastMessage = () => {
	nextTick(() => wrapper.value.scrollIntoView({ block: 'end' }))
}

watchEffect(() => {
	if (!activeSubmission.value) {
		return
	}

	messages.value = activeSubmission.value.messages

	scrollToLastMessage()
})
</script>

<template>
	<div
		class="chat-container border border-gray-300 border-l"
		:class="{ flex: activeSubmission }"
	>
		<main v-if="!activeSubmission" class="h-full w-full bg-white px-4 py-6">
			<div class="h-full overflow-hidden py-4">
				<div class="h-full overflow-y-auto">
					<h1 v-if="!activeSubmission">
						Select a conversation or send a new message
					</h1>
				</div>
			</div>
		</main>

		<main v-else class="flex flex-col h-full w-full bg-white pb-6">
			<ConversationHeader :client="activeSubmission.client" />

			<div class="h-full overflow-hidden py-4">
				<div class="h-full overflow-y-auto">
					<div ref="wrapper" class="grid grid-cols-12 gap-y-2">
						<MessageWrapper
							v-for="(message, i) in messages"
							:key="i"
							:message="message"
						/>
					</div>
				</div>
			</div>

			<div class="px-4">
				<ConversationTextInput @send-msg="handleNewMessage" />
			</div>
		</main>

		<ActionPane v-if="activeSubmission" :submission="activeSubmission" />
	</div>
</template>
