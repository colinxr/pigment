<template>
  <div class="conversations-list grow p-2">
    <header>
      <h1>Messages</h1>
    </header>
    <main>
      <SubmissionCard v-if="hasSubmissions" v-for="sub in submissions" @click="handleClick" :submission="sub" :key="i" />
    </main>
  </div>
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
