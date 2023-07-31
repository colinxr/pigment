import { defineNuxtPlugin, useRuntimeConfig } from '../../playground/.nuxt/imports'
import ApiService from './apiService'

export default defineNuxtPlugin(() => {
  const options = useRuntimeConfig().public.apiService

  const apiService = new ApiService(options.apiUrl)

  return {
    provide: { apiService }
  }
})
