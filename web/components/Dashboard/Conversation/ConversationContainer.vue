<script setup>
import { ref, nextTick } from 'vue'
import { storeToRefs } from 'pinia'

import ApiService from '@/services/ApiService'
import useSubmissionsStore from '@/stores/submissions'
import ActionPane from '@/components/ActionPane/ActionPane.vue'
import MessageWrapper from '@/components/Dashboard/SubmissionMessages/Message/MessageWrapper.vue'
import ConversationTextInput from '@/components/Dashboard/Conversation/ConversationTextInput.vue'
import ConversationHeader from '@/components/Dashboard/Conversation/ConversationHeader.vue'

const submissionsStore = useSubmissionsStore()

const props = defineProps({
	submission: {
		type: Object,
		required: true,
	},
})

provide('submission', props.submission)

const messages = ref([])
const wrapper = ref(null)

const { updateSubmissionsListOrder } = submissionsStore
const { showActionPane } = storeToRefs(submissionsStore)

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
		props.submission.value.last_message = res.data.data
		updateSubmissionsListOrder(props.submission.value.id)
	}
}

const postMessageToServer = async message => {
	const res = await ApiService.messages.post(props.submission.value.id, {
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

watchEffect(async () => {
	messages.value = props.submission.messages
	scrollToLastMessage()

	if (props.submission.has_new_messages) {
		ApiService.submissions.markAsRead(props.submission.id)
	}
})
</script>

<template>
	<main class="relative block md:flex overflow-none">
		<div class="flex flex-col h-full grow bg-white pb-6">
			<ConversationHeader :client="props.submission.client" />

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
		</div>

		<ActionPane />
	</main>
</template>
