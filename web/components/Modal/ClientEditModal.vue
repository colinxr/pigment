<template>
  <div class="bg-white border border-gray mx-auto w-full max-w-[550px] rounded-xl p-4">
    <form @submit.prevent="handleSubmit">
      <div class="mb-5">
        <TextInput
          id="first_name"
          label-text="First Name"
          :model-value="formData.first_name"
        />
      </div>

      <div class="mb-5">
        <TextInput
          id="last_name"
          label-text="Last Name"
          :model-value="formData.last_name"
        />
      </div>
      <div class="mb-5">
        <TextInput
          id="email"
          label-text="Email"
          :model-value="formData.email"
        />
      </div>
      <div>
        <button
          class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8
          text-base font-semibold text-white outline-none"
        >
          {{ btnText }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import ApiService from '@dayplanner/apiservice'
import TextInput from '@/components/Forms/TextInput.vue'

const props = defineProps({
  client: {
    type: Object,
    required: true,
  },
})

const formState = ref('')

const formData = ref({
  first_name: props.client.first_name,
  last_name: props.client.last_name,
  email: props.client.email,
})

const btnText = computed(() => {
  if (formState.value === 'pending') return 'Submitting ...'

  return 'Submit'
})

const handleSubmit = async () => {
  // validate
  // handle errors

  const res = await ApiService.clients.update(formData)

  // if error show server side validation errors
  // update formData with response data
}

</script>
