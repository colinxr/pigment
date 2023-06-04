<script setup>
  import { markRaw } from "vue"
  import ApiService from "@dayplanner/apiservice"
  import useDashboardStore from "@/stores/dashboard"
  import useModalStore from "@/stores/modal"

  import AppointmentCard from "@/components/Appointments/AppointmentCard.vue"
  import LoadingCard from "@/components/Appointments/LoadingCard.vue"
  import AppointmentCreateModal from "@/components/Modal/AppointmentCreateModal.vue"

  const modalStore = useModalStore()
  const { activeSubmission } = useDashboardStore()

  const isLoading = ref(true)
  const appointments = ref([])
  const pastAppointments = ref([])

  onMounted(async () => {
    const { data } = await ApiService.appointments.getForSubmission(
      activeSubmission.id
    )

    if (!data) return

    appointments.value = sanitizeResponseData(data.data.upcoming)
    pastAppointments.value = sanitizeResponseData(data.data.past)
    isLoading.value = false
  })

  const openModal = () => {
    modalStore.openModal({
      component: markRaw(AppointmentCreateModal),
      props: { submission: activeSubmission },
    })
  }

  const sanitizeResponseData = data => Object.values(data)
</script>

<template>
  <div>
    <div v-if="isLoading && !appointments.length" class="mb-5">
      <LoadingCard v-for="i in 3" :key="i" />
    </div>

    <div v-if="!isLoading && appointments.length" class="mb-5">
      <h5>Upcoming</h5>
      <AppointmentCard
        v-for="(appt, i) in appointments"
        :key="i"
        :appointment="appt"
        class="mb-2"
      />

      <h5 v-if="pastAppointments">Past Appointments</h5>
      <AppointmentCard
        v-for="(appt, i) in pastAppointments"
        :key="i"
        status="past"
        :appointment="appt"
        class="mb-2"
      />
    </div>

    <Button label="Add Appointment" @click="openModal" />
  </div>
</template>
