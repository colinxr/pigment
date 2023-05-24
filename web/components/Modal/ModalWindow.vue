<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="store.state.component"
        class="modal-wrapper fixed left-0 top-0 z-50 w-screen
          h-screen bg-black bg-opacity-20 grid place-items-center"
        @click.self="handleClose"
      >
        <component :is="store.state?.component" v-bind="store.state?.props" />
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import useModalStore from '@/stores/modal'

const store = useModalStore()

const handleClose = () => {
  store.closeModal()
}
</script>

<style scoped>
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: 0.25s ease all;
}
</style>
