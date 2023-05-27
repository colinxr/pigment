import { ref } from 'vue'
import { defineStore } from 'pinia'
import { AuthUserI } from './types'

const useAuthStore = defineStore('authStore', () => {
  const user = ref<AuthUserI>()

  const lastURL = ref<string>('')

  const setUser = (authUser: AuthUserI) => {
    user.value = authUser
  }

  const removeUser = async () => {
    user.value = undefined
  }

  const setLastURL = (url = '') => {
    lastURL.value = url
  }

  return {
    user,
    setUser,
    removeUser,
    lastURL,
    setLastURL,
  }
})

export default useAuthStore
