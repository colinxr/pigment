<script setup>
  import useModalStore from "@/stores/modal"
  import ApiService from "@dayplanner/apiservice"

  const store = useModalStore()

  const props = defineProps({
    appointment: {
      type: Object,
      required: true,
    },
  })

  const handleConfirm = async () => {
    try {
      const res = await ApiService.appointments.delete(props.appointment.id)

      if (res.status !== 200) handleResponseErrors(res)

      showFormAlert.value = true
      formStatus.value = "success"
      alertMessage.value = res.data.message || "Appointment Deleted"
      return
    } catch (error) {
      console.log(error)

      if (error.response?.status === 403) return

      alertMessage.value = "Something went wrong"
      formStatus.value = "error"
      showFormAlert.value = true
    }
  }

  const handleCancel = () => {
    store.closeModal()
  }
</script>

<template>
  <Card class="mx-auto max-w-[400px] rounded-xl">
    <template #content>
      <p>Are you sure you want to delete this appointment?</p>
      <p>The client will receive an email notifying them of the change.</p>

      <div class="mt-4 flex justify-end">
        <Button
          class="mr-3"
          severity="secondary"
          label="Cancel"
          @click="handleCancel"
        />

        <Button @click="handleConfirm" label="Confirm" />
      </div>
    </template>
  </Card>
</template>
