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
import useAuthStore from '@/stores/auth'
const { handleResponseErrors } = useFormErrors()


const store = useAuthStore()

const handleLogOut = async () => {
  try {
    const res = await ApiService.auth.logout()

    if (response.status !== 200) {
      return handleResponseErrors(response)
    }

    store.logout()

    return navigateTo('/login')
  } catch (error) {
    console.log(error)
  }
}

</script>
