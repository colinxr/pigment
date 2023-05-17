<template>
  <nav class="w-50 mr-10">
    <ul>
      <li>H</li>
      <li>C</li>
      <li>|||</li>
      <li @click="handleLogOut">
        Logout
      </li>
    </ul>
  </nav>
</template>

<script setup>
import ApiService from '@dayplanner/ApiService'
import useAuthStore from '@/stores/auth'

const store = useAuthStore()

const handleLogOut = async () => {
  try {
    const res = await ApiService.auth.logOut()
    store.logout()
    return navigateTo('/login')
  } catch (error) {
    console.log(error)
  }
}

</script>
