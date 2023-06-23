<script setup>
import useSubmissionsStore from '@/stores/submissions'
import SubmissionsList from '@/components/Dashboard/Submission/SubmissionsList.vue'
import ConversationContainer from '@/components/Dashboard/Conversation/ConversationContainer.vue'

const route = useRoute()
const submissionsStore = useSubmissionsStore()

const { getSubmissions, setActiveSubmission } = submissionsStore

onBeforeMount(async () => {
	console.log('testing')

	if (route.query.as) {
		setActiveSubmission(Number(route.query.as))
	}

	await getSubmissions()
})

definePageMeta({
	middleware: 'user-is-authenticated',
	keepalive: true,
})
</script>

<template>
	<div class="flex grow">
		<SubmissionsList class="w-1/4" />
		<ConversationContainer class="grow h-screen w-2/3" />
	</div>
</template>
