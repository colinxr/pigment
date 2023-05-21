import { ref } from 'vue'
import { defineStore } from 'pinia'


interface AuthUserI {
  id: number,
  first_name: string, 
  last_name: string,
  email: string, 
  username: string,
}

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
