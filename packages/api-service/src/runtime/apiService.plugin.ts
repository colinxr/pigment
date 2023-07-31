import { defineNuxtPlugin, useRuntimeConfig } from '#imports'
import ApiService from './apiService'

export default defineNuxtPlugin((nuxtApp) => {
  const options = useRuntimeConfig().public.apiService

  const apiService = new ApiService(options.apiUrl)

  return {
    provide: { apiService }
  }
})
