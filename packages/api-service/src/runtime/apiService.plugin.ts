import { defineNuxtPlugin, useRuntimeConfig } from '#app'
import ApiService from './apiService'

export default defineNuxtPlugin(() => {
  const options = useRuntimeConfig().public.apiService
  const apiService = new ApiService(options.apiUrl)

  return {
    provide: { apiService }
  }
})
