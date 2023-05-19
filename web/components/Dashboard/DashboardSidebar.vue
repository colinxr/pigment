<template>
  <div class="sidebar flex flex-row w-96 flex-shrink-0 bg-gray-100 p-4">
    <DashboardNav />
    <SubmissionsList />
  </div>
</template>

<script setup>
import DashboardNav from './DashboardNav.vue'
import SubmissionsList from './Submission/SubmissionsList.vue'
</script>
