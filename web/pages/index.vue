<template>
  <div class="dashboard flex">
    <DashboardSidebar class="w-1/3 h-screen" />

    <ConversationContainer class=" w-2/13 h-screen" />
  </div>
</template>

<script setup>
import useDashboardStore from '@/stores/dashboard'
import DashboardSidebar from '@/components/Dashboard/DashboardSidebar.vue'
import ConversationContainer from '@/components/Dashboard/Conversation/ConversationContainer.vue'

const dashboardStore = useDashboardStore()
const { getSubmissions } = dashboardStore

onBeforeMount(async () => {
  await getSubmissions()
})

definePageMeta({ middleware: 'user-is-authenticated' })
</script>

