import { defineNuxtPlugin, useRuntimeConfig } from '#app'
import ApiService from './apiService'

export default defineNuxtPlugin(() => {
  console.log(useRuntimeConfig().public);
  
  const options = useRuntimeConfig().public.apiService
  console.log(options);

  const apiService = new ApiService(options.apiUrl)

  return {
    provide: { apiService }
  }
})
