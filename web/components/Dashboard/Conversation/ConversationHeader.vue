<template>
  <header class="border-gray-300 border-b p-4 px-4 flex justify-between">
    <h2 class="font-bold text-lg">
      {{ client.full_name }}
    </h2>
    <button class="text-sm" @click="openModal">
      Edit Client
    </button>
  </header>
</template>

<script setup>
import { markRaw } from 'vue'
import useModalStore from '@/stores/modal'
import ClientEditModal from '@/components/Modal/ClientEditModal.vue'

const modalStore = useModalStore()

const props = defineProps({
  client: {
    type: Object,
    required: true,
  }
})

const openModal = () => {
  modalStore.openModal({ component: markRaw(ClientEditModal), props })
}

</script>
