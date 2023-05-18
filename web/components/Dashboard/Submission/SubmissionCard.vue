<template>
  <div @click="handleClick" class="conversation-card card space-between items-center p-4 h-20 hover:cursor-pointer">
    <div class="avatar w-10">
      <div class="rounded-full bg-slate-600 ">
        <div class="w-10 h-full flex items-center justify-center">
          <span class="text-xl font-bold ">{{ client.initials }}</span>
        </div>
      </div>
    </div>

    <div class="card-body grow ml-5">
      <div class="flex items-center ">
        <span class="card-title p mr-1">{{ client.first_name }}</span>
        <Badge :status="submission.status" />
      </div>
      <p class="text-sm">{{ last_message.preview }}</p>
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

const { client, last_message } = submission

const handleClick = () => {
  console.log('got here');
  dashboardStore.setActiveSubmission(submission)
}
</script>

<style>
.card {
  flex-direction: row;
}

.card:hover {
  @apply bg-slate-100;
}

.card-body {
  padding: 0;
  gap: 0;
}

.card-title {
  font-size: 1rem;
}
</style>
