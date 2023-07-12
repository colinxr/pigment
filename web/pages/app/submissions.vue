<script setup>
import useSubmissionsStore from '@/stores/submissions'
import SubmissionsList from '@/components/Dashboard/Submission/SubmissionsList.vue'
import ConversationContainer from '@/components/Dashboard/Conversation/ConversationContainer.vue'
import EmptyConversation from '@/components/Dashboard/Conversation/EmptyConversation.vue'
import { storeToRefs } from 'pinia'

const route = useRoute()
const submissionsStore = useSubmissionsStore()

const { getSubmissions, setActiveSubmission, clearActiveSubmission } =
	submissionsStore

const { activeSubmission } = storeToRefs(submissionsStore)

onBeforeMount(async () => {
	if (route.query.as) {
		setActiveSubmission(Number(route.query.as))
	}

	await getSubmissions()
})

watchEffect(() => {
	if (!route.query.as) clearActiveSubmission()
})

definePageMeta({
	middleware: 'user-is-authenticated',
	keepalive: true,
})
</script>

<template>
	<div class="flex grow h-full">
		<SubmissionsList
			class="w-full md:w-1/4"
			:class="{ 'hidden md:block': activeSubmission }"
		/>

		<div
			class="chat-container border border-gray-300 border-l"
			:class="{
				block: actionSubmission,
				'hidden md:block': !activeSubmission,
			}"
		>
			<EmptyConversation v-if="!activeSubmission" />

			<ConversationContainer
				v-else
				:submission="activeSubmission"
				class="grow h-screen md:w-2/3"
			/>
		</div>
	</div>
</template>
