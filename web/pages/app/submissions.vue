<script setup>
import useSubmissionsStore from '@/stores/submissions'
import SubmissionsList from '@/components/Dashboard/Submission/SubmissionsList.vue'
import ConversationContainer from '@/components/Dashboard/Conversation/ConversationContainer.vue'

const route = useRoute()
const submissionsStore = useSubmissionsStore()

const { getSubmissions, setActiveSubmission, activeSubmission } =
	submissionsStore

onBeforeMount(async () => {
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
		<SubmissionsList
			class="md:w-1/4"
			:class="{ 'hidden md:block': activeSubmission }"
		/>
		<ConversationContainer class="grow h-screen md:w-2/3" />
	</div>
</template>

on mobile // if active submisison, hide the submissions list,
