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

const dashboardStore = useDashboardStore()
const { getSubmissions } = dashboardStore

onBeforeMount(async () => {
  await getSubmissions()
})

definePageMeta({
  middleware: 'user-is-authenticated',
  layout: 'default',
})
</script>
