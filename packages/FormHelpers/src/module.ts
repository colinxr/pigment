import { defineNuxtModule, createResolver, addComponent, installModule, addImportsDir } from '@nuxt/kit'
import { generateClasses } from '@formkit/themes'

// Module options TypeScript interface definition
export interface ModuleOptions {}

export default defineNuxtModule<ModuleOptions>({
  meta: {
    name: 'form-module',
    configKey: 'formModule'
  },
  async setup (options, nuxt) {
    const { resolve } = createResolver(import.meta.url)

    addImportsDir(resolve('runtime/composables'))

    addComponent({
      name: 'DynamicForm', 
      filePath: resolve('runtime/components/DynamicForm.vue')
    })

    addComponent({
      name: 'AutoCompleteInput', 
      filePath: resolve('runtime/components/AutoCompleteInput.vue')
    })

    addComponent({
      name: 'TextInput', 
      filePath: resolve('runtime/components/TextInput.vue')
    })


    // Alert Components 
    addComponent({
      name: 'AlertWrapper', 
      filePath: resolve('runtime/components/Alerts/AlertWrapper.vue')
    })

    await installModule('@formkit/nuxt', {
      exposeConfig: true,
      config: {
        classes: generateClasses({
          global: {
            outer: 'mb-5 flex flex-col',
            label: 'mb-1 block text-base',
            input: 'w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-4 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md',
          },
        }),
      }
    })

    await installModule('@nuxtjs/tailwindcss', {
      // module configuration
      exposeConfig: true,
      config: {
        content: {
          files: [
            resolve('./runtime/components/**/*.{vue,mjs,ts}'),
            resolve('./runtime/*.{mjs,js,ts}'),
          ]
        },
        safelist: [
          'flex',
          'bg-yellow-100',
          'text-yellow-700',
          'bg-red-100',
          'rounded-lg',
          'p-4',
          'mb-4',
          'text-sm',
          'text-red-700',
          'h-5',
          'inline',
          'mr-3',
          'font-medium',
          'bg-green-100',
          'text-green-700',
          'p-button',
          'p-component', 
          'form__submit', 
          'flex', 
          'align-center', 
          'justify-center',
          'bg-[#6A64F1]',
        ],
      }
    })

    // const { resolve } = createimport.meta.url);
    // const runtimeDir = fileURLToPath(new URL('./runtime', import.meta.url));

    // nuxt.hook('nitro:config', (nitroConfig) => {
    //   if (!nitroConfig.imports) {
    //     nitroConfig.imports = {
    //       imports: [],
    //     };
    //   }

    //   nitroConfig.imports.imports?.push({
    //     name: 'useFormErrors',
    //     as: 'useFormErrors',
    //     from: join(runtimeDir, 'composables/useFormErrors'),
    //   });
    // });
  }
})
