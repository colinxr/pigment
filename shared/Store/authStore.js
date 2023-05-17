import { ref } from 'vue'
import { defineStore } from 'pinia'

const useAuthStore = defineStore('authStore', () => {
  const user = ref(undefined)

  const setUser = (authUser) => {
    user.value = authUser
  }

  const removeUser = async () => {
    user.value = undefined
  }

  return {
    user,
    setUser,
    removeUser,
  }
})

export default useAuthStore
