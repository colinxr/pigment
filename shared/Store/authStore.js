import ApiService from '@dayplanner/apiservice'
import { defineStore } from 'pinia'

const useAuthStore = defineStore('authStore', {
  user: () => undefined,

  getters: {
    isAuthenticated() {
      return !!this.user.value
    },
  },

  actions: {
    login(user) {
      this.user = user
    },

    async logout() {
      const res = await ApiService.auth.logout()
      console.log(res)

      this.user = undefined
    },
  },
})

export default useAuthStore
