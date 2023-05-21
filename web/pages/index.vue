<template>
  <div class="dashboard h-screen overflow-hidden flex items-center">
    <DashboardSidebar class="h-screen" />

    <ConversationContainer class="grow h-screen" />
  </div>
</template>

<script setup>
import useDashboardStore from '@/stores/dashboard'
import DashboardSidebar from '@/components/Dashboard/DashboardSidebar.vue'
import ConversationContainer from '@/components/Dashboard/SubmissionMessages/ConversationContainer.vue'

const dashboardStore = useDashboardStore()
const { getSubmissions } = dashboardStore

onBeforeMount(async () => {
  await getSubmissions()
})

definePageMeta({ middleware: 'user-is-authenticated' })
</script>

