<template>
  <aside class="w-1/2 p-4 bg-gray-300">
    <div class="h-full">
      <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
        <button type="button" @click="currentView = 'client'"
          class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 active"
          id="tabs-with-underline-item-1" data-hs-tab="#tabs-with-underline-1" aria-controls="tabs-with-underline-1"
          role="tab">
          Client
        </button>
        <button type="button" @click="currentView = 'images'"
          class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600"
          id="tabs-with-underline-item-2" data-hs-tab="#tabs-with-underline-2" aria-controls="tabs-with-underline-2"
          role="tab">
          Images
        </button>
        <button type="button" @click="currentView = 'appointments'"
          class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600"
          id="tabs-with-underline-item-3" data-hs-tab="#tabs-with-underline-3" aria-controls="tabs-with-underline-3"
          role="tab">
          Appointments
        </button>
      </nav>

      <div class="mt-3">
        <ActionPaneClient v-if="currentView === 'client'" :client="submission.client" />
        <div v-else>testetsetset</div>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref } from 'vue'
import ActionPaneClient from './ActionPaneClient.vue';

const props = defineProps({
  submission: {
    type: Object,
    required: true,
  }
})

const currentView = ref('client')


</script>