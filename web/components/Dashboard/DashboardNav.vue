<template>
  <nav class="w-50 mr-10">
    <ul>
      <li>home</li>
      <li>clients</li>
      <li>setings</li>
      <li @click="handleLogOut">Logout</li>
    </ul>
  </nav>
</template >

<script setup >
import useAuthStore from '@/stores/auth'
import ApiService from '~/../shared/ApiService';

const store = useAuthStore()

const handleLogOut = async () => {
  try {
    const res = await ApiService.auth.logOut()
    store.logout()
    return navigateTo('/login')
  } catch (error) {
    console.log(error);
  }

}

</script>
            