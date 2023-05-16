<template>
  <div class="dashboard flex">
    <DashboardSidebar class="w-1/3 h-screen" />

    <main class=" conversation w-2/3 h-screen">
      <div v-if="!activeConversation" class="h-screen flex items-center justify-center">
        Select a conversation or send a new message
      </div>
    </main>
  </div>
</template>

<script setup>
import useAuthStore from '@/stores/auth'
import ApiService from '@dayplanner/ApiService'
import DashboardSidebar from '@/components/Dashboard/DashboardSidebar'

const store = useAuthStore()

console.log(store.user)

const getUser = async () => {
  const res = await ApiService.auth.getAuthenticatedSession()

  console.log(res)
}

const handleLogOut = async () => {
  const res = await ApiService.auth.logOut()

  store.logout()

  return navigateTo('/login')
}
</script>