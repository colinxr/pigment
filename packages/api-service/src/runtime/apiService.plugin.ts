import { defineNuxtPlugin, useRuntimeConfig } from '../../_playground/.nuxt/imports'
import ApiService from './apiService'

export default defineNuxtPlugin((nuxtApp) => {
  const options = useRuntimeConfig().public.apiService

  const apiService = new ApiService(options.apiUrl)

  return {
    provide: { apiService }
  }
})
