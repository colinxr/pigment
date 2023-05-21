import { ref } from 'vue'
import { defineStore } from 'pinia'


interface AuthUser {
  id: number,
  first_name: string, 
  last_name: string,
  email: string, 
  username: string,
}

const useAuthStore = defineStore('authStore', () => {
  const user = ref<AuthUser>()

  const setUser = (authUser: AuthUser) => {
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
