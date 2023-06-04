<script setup>
  import ModalWindow from "@/components/Modal/ModalWindow.vue"
  import AppSidebar from "@/components/PrimeVue/AppSidebar.vue"
</script>

<template>
  <div class="dashboard h-screen overflow-hidden flex align-start">
    <AppSidebar />
    <slot />
    <ModalWindow />
  </div>
</template>
