<template>
  <div class="bg-white border border-gray mx-auto w-full max-w-[550px] rounded-xl p-4">
    <header class="flex space-between">
      <h1>Update Client</h1>
      <span class="cursor-pointer" @click="store.closeModal">X</span>
    </header>

    <DynamicForm :schema="formSchema" @submit="handleSubmit" />
  </div>
</template>

<script setup>
import { string } from 'yup'

import ApiService from '@dayplanner/apiservice'
import useModalStore from '@/stores/modal'
import useFormErrors from '@/composables/useFormErrors'

import DynamicForm from '../Forms/DynamicForm.vue'

const { errorState, handleResponseErrors } = useFormErrors()

const store = useModalStore()

const props = defineProps({
  client: {
    type: Object,
    required: true
  }
})

const formSchema = {
  fields: [
    {
      label: 'Client First Name *',
      name: 'first_name',
      as: 'input',
      value: props.client.first_name,
      rules: string().required()
    },
    {
      label: 'Client Last Name *',
      name: 'last_name',
      as: 'input',
      value: props.client.last_name,
      rules: string().required()
    },
    {
      label: 'Client Email *',
      name: 'email',
      as: 'input',
      value: props.client.email,
      rules: string().email().required()
    }
  ]
}

const handleSubmit = async () => {
  try {
    const res = await ApiService.clients.update(formData)

    if (res.status !== 200) {
      return handleResponseErrors(response)
    }

    return 'tk'
  } catch (error) {
    errorState.isSet = true
    errorState.message = 'something went wrong'
    return false
  }
}
</script>
