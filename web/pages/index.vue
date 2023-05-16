<template>
  <div>
    app index

    <button class="btn mt-5" type="submit" @click="handleLogOut">Log Out</button>

    <div>
      <button class="btn mt-5" type="submit" @click="getUser">Get User</button>
    </div>
  </div>
</template>

<script setup>
import ApiService from '@dayplanner/ApiService'

import useAuthStore from '@/stores/auth'

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