import { defu } from 'defu'
import { defineNuxtModule, addPlugin, createResolver } from '@nuxt/kit'

// Module options TypeScript interface definition
export interface ApiServiceOptions {
  apiUrl: string,
}

declare module '@nuxt/schema' {
  interface ConfigSchema {
    publicRuntimeConfig?: {
      apiService?: ApiServiceOptions
    }
  }
  interface NuxtConfig {
    apiService?: ApiServiceOptions
  }
  interface NuxtOptions {
    apiService?: ApiServiceOptions
  }
}

export default defineNuxtModule<ApiServiceOptions>({
  meta: {
    name: '@pigment/api-service',
    configKey: 'apiService'
  },

  defaults: {
    apiUrl: process.env.NUXT_ENV_API_URL || ''
  },

  setup (options, nuxt) {
    console.log(nuxt.options.apiService);
    
    nuxt.options.runtimeConfig.public.apiService = defu(nuxt.options.runtimeConfig.public.apiService, {
      apiUrl: options.apiUrl
    })
    
    const { resolve } = createResolver(import.meta.url)
    // Do not add the extension since the `.ts` will be transpiled to `.mjs` after `npm run prepack`
    addPlugin(resolve('./runtime/apiService.plugin'))
  }
})
