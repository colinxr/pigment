<template>
  <div class="Submissions_list flex flex-col w-full h-full pl-4 pr-4 py-4 -mr-4">
    <!-- Submissions Headers -->
    <div class="flex flex-row items-center">
      <div class="flex flex-row items-center">
        <div class="text-xl font-semibold">Messages</div>
        <!-- <div class="flex items-center justify-center ml-2 text-xs h-5 w-5 text-white bg-red-500 rounded-full font-medium"> -->
        <!-- 5</div> -->
      </div>

      <div class="ml-auto">
        <button class="flex items-center justify-center h-7 w-7 bg-gray-200 text-gray-500 rounded-full">
          <svg class="w-4 h-4 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </button>
      </div>
    </div>

    <div class="mt-2">
      <SubmissionCard v-if="hasSubmissions" v-for="(sub, i) in submissions" @click="handleClick" :submission="sub"
        :key="i" />
    </div>

    <!-- <div class="h-full overflow-hidden relative pt-2">
      <div class="flex flex-col divide-y h-full overflow-y-auto -mx-4">
        <div class="flex flex-row items-center p-4 relative">
          <div class="absolute text-xs text-gray-500 right-0 top-0 mr-4 mt-3">2 hours ago</div>
          <div
            class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-500 text-pink-300 font-bold flex-shrink-0">
            T
          </div>
          <div class="flex flex-col flex-grow ml-3">
            <div class="text-sm font-medium">Flo Steinle</div>
            <div class="text-xs truncate w-40">Good after noon! how can i help you?</div>
          </div>
          <div class="flex-shrink-0 ml-2 self-end mb-1">
            <span class="flex items-center justify-center h-5 w-5 bg-red-500 text-white text-xs rounded-full">3</span>
          </div>
        </div>
        <div class="flex flex-row items-center p-4">
          <div
            class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-500 text-pink-300 font-bold flex-shrink-0">
            T
          </div>
          <div class="flex flex-col flex-grow ml-3">
            <div class="flex items-center">
              <div class="text-sm font-medium">Sarah D</div>
              <div class="h-2 w-2 rounded-full bg-green-500 ml-2"></div>
            </div>
            <div class="text-xs truncate w-40">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis,
              doloribus?</div>
          </div>
        </div>
        <div class="flex flex-row items-center p-4">
          <div
            class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-500 text-pink-300 font-bold flex-shrink-0">
            T
          </div>
          <div class="flex flex-col flex-grow ml-3">
            <div class="flex items-center">
              <div class="text-sm font-medium">Sarah D</div>
              <div class="h-2 w-2 rounded-full bg-green-500 ml-2"></div>
            </div>
            <div class="text-xs truncate w-40">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis,
              doloribus?</div>
          </div>
        </div>
        <div class="flex flex-row items-center p-4">
          <div
            class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-500 text-pink-300 font-bold flex-shrink-0">
            T
          </div>
          <div class="flex flex-col flex-grow ml-3">
            <div class="flex items-center">
              <div class="text-sm font-medium">Sarah D</div>
              <div class="h-2 w-2 rounded-full bg-green-500 ml-2"></div>
            </div>
            <div class="text-xs truncate w-40">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis,
              doloribus?</div>
          </div>
        </div>
        <div class="flex flex-row items-center p-4">
          <div
            class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-500 text-pink-300 font-bold flex-shrink-0">
            T
          </div>
          <div class="flex flex-col flex-grow ml-3">
            <div class="flex items-center">
              <div class="text-sm font-medium">Sarah D</div>
              <div class="h-2 w-2 rounded-full bg-green-500 ml-2"></div>
            </div>
            <div class="text-xs truncate w-40">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis,
              doloribus?</div>
          </div>
        </div>
      </div>
      <div class="absolute bottom-0 right-0 mr-2">
        <button class="flex items-center justify-center shadow-sm h-10 w-10 bg-red-500 text-white rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
            </path>
          </svg>
        </button>
      </div>
    </div> -->
  </div>
  <!-- <div class="conversations-list grow p-2">
    <header>
      <h1>Messages</h1>
    </header>
    <main>
      <SubmissionCard v-if="hasSubmissions" v-for="sub in submissions" @click="handleClick" :submission="sub" :key="i" />
    </main>
  </div> -->
</template>

<script setup>
import { storeToRefs } from 'pinia'

import useDashboardStore from '@/stores/dashboard'
import SubmissionCard from './SubmissionCard.vue'

const dashboardStore = useDashboardStore()
const { submissions } = storeToRefs(dashboardStore)
console.log(submissions);
const hasSubmissions = computed(() => submissions.value.length)
</script>
