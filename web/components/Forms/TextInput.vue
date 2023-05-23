<template>
  <label :for="id" class="mb-3 block text-base font-medium text-[#07074D]">
    {{ labelText }}
  </label>
  <input
    :id="id"
    type="text"
    :name="id"
    :value="modelValue"
    class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium
        text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
    @change="$emit('update:modelValue', $event.target.value)"
  >
</template>

<script setup>
const props = defineProps({
  id: {
    type: String,
    required: true,
  },
  labelText: {
    type: String,
    required: true,
  },
  modelValue: {
    type: String,
    required: true,
  },
})
</script>
