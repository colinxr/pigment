<template>
  <div class="bg-white border border-gray mx-auto w-full max-w-[800px] rounded-xl p-4">
    <header>
      <div class="flex justify-between mb-2">
        <h1>Create Appointment</h1>
        <span class="cursor-pointer" @click="store.closeModal">X</span>
      </div>

      <AlertWrapper v-if="showFormAlert" :status="formStatus" :msg="alertMessage" />
    </header>

    <DynamicForm
      form-id="appointment-create"
      :schema="formSchema"
      :data="initialValues"
      :error-state="errorState"
      @form-submitted="handleSubmit"
    />
  </div>
</template>

<script setup>
import { getTimeZoneOffset } from '@/services/dateService'
import ApiService from '@dayplanner/apiservice'
import useModalStore from '@/stores/modal'
import useFormErrors from '@/composables/useFormErrors'
import DynamicForm from '@/components/Forms/DynamicForm.vue'
import AlertWrapper from '@/components/Alerts/AlertWrapper.vue'

const { errorState, handleResponseErrors } = useFormErrors()

const store = useModalStore()

const props = defineProps({
  submission: {
    type: Object,
    required: true,
  },
})

const initialValues = {
  name: `${props.submission.client.full_name}`,
  description: `${props.submission.idea}`,
  startDateTime: null,
  duration: 1,
  price: null,
  deposit: null,
}

const showFormAlert = ref(false)
const formStatus = ref('')
const alertMessage = ref('')

const formSchema = [
  {
    $formkit: 'text',
    label: 'Appointment Name',
    name: 'name',
    validation: 'required',
    value: 'test',
    validationVisibility: 'dirty',
  },
  {
    $formkit: 'textarea',
    label: 'Description',
    name: 'description',
    validation: 'required',
    validationVisibility: 'dirty',
  },
  {
    $el: 'div',
    attrs: {
      class: 'flex gap-4'
    },
    children: [
      {
        $formkit: 'datetime-local',
        label: 'Start Time',
        name: 'startDateTime',
        validation: 'required',
        validationVisibility: 'dirty',
        'outer-class': 'w-1/2',
      },
      {
        $formkit: 'number',
        label: 'Appointment Duration',
        name: 'duration',
        validation: 'required',
        help: 'How long is the appointment going to take?',
        validationVisibility: 'dirty',
        'outer-class': 'w-1/2',
      },
    ]
  },
  {
    $el: 'div',
    attrs: {
      class: 'flex gap-4'
    },
    children: [
      {
        $formkit: 'number',
        label: 'Price',
        name: 'price',
        validation: 'required',
        validationVisibility: 'dirty',
        'outer-class': 'w-1/2',
      },
      {
        $formkit: 'number',
        label: 'Deposit',
        name: 'deposit',
        'outer-class': 'w-1/2',
      },
    ]
  }
]

const handleSubmit = async (formData) => {
  showFormAlert.value = false

  try {
    const timezone = getTimeZoneOffset()
    formData.startDateTime = `${formData.startDateTime}:00${timezone}`

    console.log(formData)

    const res = await ApiService.appointments.store(props.submission.id, formData)

    if (res.status !== 200) {
      handleResponseErrors(res)
    }

    showFormAlert.value = true
    formStatus.value = 'success'
    alertMessage.value = res.data.message || 'Appointment created'
    return
  } catch (error) {
    console.log(error)

    alertMessage.value = 'something went wrong'
    formStatus.value = 'error'
    showFormAlert.value = true
  }
}
</script>
