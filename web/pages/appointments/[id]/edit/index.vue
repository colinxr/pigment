<script setup>
  import ApiService from "@dayplanner/apiservice"
  import useFormErrors from "@/composables/useFormErrors"
  import DynamicForm from "@/components/Forms/DynamicForm.vue"
  import AlertWrapper from "@/components/Alerts/AlertWrapper.vue"

  import { convertToIsoString, getDuration } from "@/services/dateService"

  const route = useRoute()
  const { errorState, handleResponseErrors } = useFormErrors()

  const loading = ref(true)
  const appointment = ref({})
  const initialValues = {}

  const showFormAlert = ref(false)
  const formStatus = ref("")
  const alertMessage = ref("")

  const formSchema = [
    {
      $formkit: "text",
      label: "Appointment Name",
      name: "name",
      validation: "required",
      value: "test",
      validationVisibility: "dirty",
    },
    {
      $formkit: "textarea",
      label: "Description",
      name: "description",
      validation: "required",
      validationVisibility: "dirty",
    },
    {
      $el: "div",
      attrs: {
        class: "flex gap-4",
      },
      children: [
        {
          $formkit: "datetime-local",
          label: "Start Time",
          name: "startDateTime",
          validation: "required",
          validationVisibility: "dirty",
          "outer-class": "w-1/2",
        },
        {
          $formkit: "number",
          label: "Appointment Duration",
          name: "duration",
          validation: "required",
          help: "How long is the appointment going to take?",
          validationVisibility: "dirty",
          "outer-class": "w-1/2",
        },
      ],
    },
    {
      $el: "div",
      attrs: {
        class: "flex gap-4",
      },
      children: [
        {
          $formkit: "number",
          label: "Price",
          name: "price",
          validation: "required",
          validationVisibility: "dirty",
          "outer-class": "w-1/2",
        },
        {
          $formkit: "number",
          label: "Deposit",
          name: "deposit",
          "outer-class": "w-1/2",
        },
      ],
    },
  ]

  onBeforeMount(async () => {
    const { data } = await ApiService.appointments.show(route.params.id)

    appointment.value = data.data

    initialValues.name = data.data.name
    initialValues.description = data.data.description
    initialValues.startDateTime = convertToIsoString(data.data.startDateTime)
    initialValues.duration = getDuration(
      data.data.startDateTime,
      data.data.endDateTime
    )
    initialValues.price = data.data.price
    initialValues.deposit = data.data.deposit

    loading.value = false
  })
</script>

<template>
  <div class="layout-main p-4 w-full">
    <h2 class="text-xl font-semibold mb-5">Edit Appointment</h2>

    <Card class="w-full">
      <template #content v-if="!loading && appointment">
        <DynamicForm
          form-id="appointment-edit"
          :schema="formSchema"
          :data="initialValues"
          :error-state="errorState"
          @form-submitted="handleSubmit"
        />
      </template>
    </Card>
  </div>
</template>
