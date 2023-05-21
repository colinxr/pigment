import { ref } from 'vue'
import { defineStore } from 'pinia'
import { AuthUserI } from './types'

const useAuthStore = defineStore('authStore', () => {
  const user = ref<AuthUserI>()

  const setUser = (authUser: AuthUserI) => {
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
