<template>
  <div class="sub-card flex flex-col -mx-4 hover:cursor-pointer" @click="handleClick">
    <div class="relative flex flex-row items-center p-4">
      <!-- timestamp -->
      <div class="absolute text-xs text-gray-500 right-0 top-0 mr-4 mt-3">{ timestamp }</div>

      <!-- client avatar -->
      <div
        class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-500 text-pink-300 font-bold flex-shrink-0">
        {{ client.initials }}
      </div>


      <!-- submission body -->
      <div class="flex flex-col flex-grow ml-3">
        <div class="text-sm font-medium">
          {{ client.full_name }}
          <Badge :status="submission.status" />
        </div>
        <div class="text-xs truncate w-40">{{ last_message.preview }}</div>
      </div>

      <!-- new messages indicator -->
      <div class="flex-shrink-0 ml-2 self-end mb-1">
        <span class="flex items-center justify-center h-5 w-5 bg-red-500 text-white text-xs rounded-full">5</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import useDashboardStore from '@/stores/dashboard'
import Badge from '~/components/Badge.vue';

const dashboardStore = useDashboardStore();

const { submission } = defineProps({
  submission: {
    type: Object,
    required: true,
  }
})

console.log(submission);

const { client, last_message } = submission

const handleClick = () => {
  dashboardStore.setActiveSubmission(submission)
}
</script>

<style>
.submission:hover {
  @apply bg-slate-100;
}
</style>
