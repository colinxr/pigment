import { defineStore } from 'pinia';

export default defineStore('authStore', {
  user: () => undefined,

  getters: {
    isAuthenticated() {
      return !!this.user.value;
    },
  },

  actions: {
    login(user) {
      this.user = user;
    },

    logout() {
      this.user = undefined;
    },
  },
});
