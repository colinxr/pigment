<template>
  <div>
    <div v-if="appointments.length">
      <AppointmentCard v-for="(item, index) in 2" :key="index" />
    </div>
    <button class="btn" @click="openModal">
      Add Appointment
    </button>
  </div>
</template>

<script setup>

import { markRaw } from 'vue'
import ApiService from '@dayplanner/apiservice'
import useDashboardStore from '@/stores/dashboard'
import useModalStore from '@/stores/modal'

import AppointmentCard from '@/components/Appointments/AppointmentCard.vue'
import AppointmentCreateModal from '@/components/Modal/AppointmentCreateModal.vue'

const modalStore = useModalStore()
const { activeSubmission } = useDashboardStore()

const appointments = ref([])

onMounted(async () => {
  const res = await ApiService.submissions.getAppointments(activeSubmission.id)

  if (res.data) {
    appointments.value = res.data
  }
})

const openModal = () => {
  modalStore.openModal({
    component: markRaw(AppointmentCreateModal),
    props: { submission: activeSubmission }
  })
}

</script>

<!-- fetch appointments for this submission.  -->

<!-- list of appointments  -->

<!-- create new appointment button  -->

<!-- open appointment edit modal  -->

<!-- edit an appointment  -->
