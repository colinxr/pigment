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

    logout() {
      this.user = undefined
    },
  },
})

export default useAuthStore
