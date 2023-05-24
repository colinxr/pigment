<template>
  <div class="flex grow">
    <SubmissionsList class="w-1/4" />
    <ConversationContainer class="grow h-screen w-2/3" />
  </div>
</template>

<script setup>
import useDashboardStore from '@/stores/dashboard'
import SubmissionsList from '@/components/Dashboard/Submission/SubmissionsList.vue'
import ConversationContainer from '@/components/Dashboard/ConversationContainer.vue'

definePageMeta({
  middleware: 'user-is-authenticated',
})

const dashboardStore = useDashboardStore()
const { getSubmissions } = dashboardStore

onBeforeMount(async () => {
  await getSubmissions()
})
</script>
