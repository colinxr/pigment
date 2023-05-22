<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div class="modal-wrapper fixed left-0 top-0 z-50 w-screen h-screen bg-black bg-opacity-20 grid place-items-center">
        <component :is="" />
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>

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