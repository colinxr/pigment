<template>
  hecck ya

  <button @click="openModal">Edit</button>
</template>

<script setup>
import useModalStore from '@/stores/modal'
import ClientEditModal from '@/components/Modal/ClientEditModal.vue';

const modalStore = useModalStore()

const props = defineProps({
  client: {
    type: Object,
    required: true,
  }
})

const openModal = () => {
  modalStore.openModal({ component: ClientEditModal, props })
}

</script>