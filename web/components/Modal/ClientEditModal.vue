<template>
  <div class="bg-white border border-gray mx-auto w-full max-w-[550px] rounded-xl p-4">
    <form @submit.prevent="handleSubmit">
      <div class="mb-5">
        <label for="first_name" class="mb-3 block text-base font-medium text-[#07074D]">
          First Name
        </label>
        <input v-model="formData.first_name" type="text" name="first_name" id="first_name" placeholder="First Name"
          class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
      </div>
      <div class="mb-5">
        <label for="last_name" class="mb-3 block text-base font-medium text-[#07074D]">
          Last Name
        </label>
        <input v-model="formData.last_name" type="text" name="last_name" id="last_name" placeholder="Last Name"
          class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
      </div>
      <div class="mb-5">
        <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">
          Email Address
        </label>
        <input v-model="formData.email" type="email" name="email" id="email"
          class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
      </div>
      <div>
        <button
          class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">
          {{ btnText }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import ApiService from '@dayplanner/apiservice'

const props = defineProps({
  client: {
    type: Object,
    required: true,
  }
})

const formState = ref('')

const formData = ref({
  first_name: props.client.first_name,
  last_name: props.client.last_name,
  email: props.client.email,
})

const btnText = computed(() => {
  if (formState === 'pending') return 'Submitting ...'

  return 'Submit'
})

const handleSubmit = async () => {
  const res = await ApiService.clients.update(formData)
}

</script>