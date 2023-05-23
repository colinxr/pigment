<template>
  <div class="dashboard h-screen overflow-hidden flex align-start">
    <DashboardNav />
    <slot />
    <ModalWindow />
  </div>
</template>

<script setup>
import ModalWindow from '@/components/Modal/ModalWindow.vue'
</script>
