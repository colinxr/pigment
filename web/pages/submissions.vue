<script setup>
import useDashboardStore from '@/stores/dashboard'
import SubmissionsList from '@/components/Dashboard/Submission/SubmissionsList.vue'
import ConversationContainer from '@/components/Dashboard/Conversation/ConversationContainer.vue'

const route = useRoute()

definePageMeta({
	middleware: 'user-is-authenticated',
	keepalive: true,
})

const dashboardStore = useDashboardStore()
const { getSubmissions, findSubmissionById, setActiveSubmission } =
	dashboardStore

onBeforeMount(async () => {
	await getSubmissions()
	if (route.query.as) {
		const submission = findSubmissionById(Number(route.query.as))
		setActiveSubmission(submission)
	}
})

onMounted(() => {})
</script>

<template>
	<div class="flex grow">
		<SubmissionsList class="w-1/4" />
		<ConversationContainer class="grow h-screen w-2/3" />
	</div>
</template>
