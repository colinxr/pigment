<template>
  <div class="bg-white border border-gray mx-auto w-full max-w-[550px] rounded-xl p-4">
    <header class="flex space-between">
      <h1>Update Client</h1>
      <span class="cursor-pointer" @click="store.closeModal">X</span>
    </header>

    <DynamicForm :schema="formSchema" :data="props.client" @submit="handleSubmit" />
  </div>
</template>

<script setup>
// import ApiService from '@dayplanner/apiservice'
import useModalStore from '@/stores/modal'

import DynamicForm from '@/components/Forms/DynamicForm.vue'

const store = useModalStore()

const props = defineProps({
  client: {
    type: Object,
    required: true,
  },
})

const formSchema = [
  {
    $formkit: 'text',
    label: 'Client First Name *',
    name: 'first_name',
    validation: 'required',
  },
  {
    $formkit: 'text',
    label: 'Client Last Name *',
    name: 'last_name',
    validation: 'required',
  },
  {
    $formkit: 'text',
    label: 'Client Email *',
    name: 'email',
    validation: 'required|email',
  },
]

const handleSubmit = async (values) => {
  await console.log(values)
  return false

  // try {
  // const res = await ApiService.clients.update(formData)

  // if (res.status !== 200) {
  // return handleResponseErrors(response)
  // }

  // return 'tk'
  // } catch (error) {
  //   errorState.isSet = true
  //   errorState.message = 'something went wrong'
  //   return false
  // }
}
</script>
