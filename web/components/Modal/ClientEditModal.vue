<template>
  <div class="bg-white border border-gray mx-auto w-full max-w-[550px] rounded-xl p-4">
    <header>
      <div class="flex justify-between mb-2">
        <h1>Update Client</h1>
        <span class="cursor-pointer" @click="store.closeModal">X</span>
      </div>

      <AlertWrapper v-if="formStatus" :status="formStatus" :msg="feedBackMessage" />
    </header>

    <DynamicForm
      form-id="update-client"
      :schema="formSchema"
      :data="props.client"
      :error-state="errorState"
      @form-submitted="handleSubmit"
    />
  </div>
</template>

<script setup>
import ApiService from '@dayplanner/apiservice'
import useModalStore from '@/stores/modal'
import useFormErrors from '@/composables/useFormErrors'
import DynamicForm from '@/components/Forms/DynamicForm.vue'

const { errorState, handleResponseErrors } = useFormErrors()

const store = useModalStore()

const props = defineProps({
  client: {
    type: Object,
    required: true,
  },
})

const formStatus = ref('')
const feedBackMessage = ref('')

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

watch(
  () => formStatus,
  () => {
    if (formStatus.value === 'success') {
      formAlert.value = SuccessAlert
    }
  }
)

const handleSubmit = async (formData) => {
  formStatus.value = 'loading'
  try {
    const res = await ApiService.clients.update(formData)

    if (res.status !== 200) {
      handleResponseErrors(res)
    }

    formStatus.value = 'success'
    feedBackMessage.value = res.message
    return
  } catch (error) {
    console.log(error)
    errorState.isSet = true
    errorState.message = 'Something went wrong'
    formStatus.value = 'error'
  }
}
</script>
