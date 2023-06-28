<script setup>
import { ref, nextTick } from 'vue'
import { storeToRefs } from 'pinia'

import ApiService from '@dayplanner/ApiService'
import useSubmissionsStore from '@/stores/submissions'
import ActionPane from '@/components/ActionPane/ActionPane.vue'
import MessageWrapper from '@/components/Dashboard/SubmissionMessages/Message/MessageWrapper.vue'
import ConversationTextInput from '@/components/Dashboard/Conversation/ConversationTextInput.vue'
import ConversationHeader from '@/components/Dashboard/Conversation/ConversationHeader.vue'

const submissionsStore = useSubmissionsStore()

const messages = ref([])
const wrapper = ref(null)

const { updateSubmissionsListOrder } = submissionsStore
const { activeSubmission } = storeToRefs(submissionsStore)

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

watch(activeSubmission, async newVal => {
	messages.value = newVal.messages

	scrollToLastMessage()

	const res = await ApiService.submissions.markAsRead(newVal.id)
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
					<div ref="wrapper" class="flex flex-col">
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
