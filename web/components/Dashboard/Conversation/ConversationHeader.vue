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

<template>
  <header class="border-gray-100 border-b py-3 px-4 flex justify-between">
    <span class="font-bold text-lg ">
      {{ client.full_name }}
    </span>
    <button class="text-sm" @click="openModal">
      Edit Client
    </button>
  </header>
</template>
