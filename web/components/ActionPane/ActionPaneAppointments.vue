<template>
  <div>
    <div v-if="appointments.length" class="mb-5">
      <AppointmentCard v-for="(item, index) in 2" :key="index" />
    </div>
    <button class="btn btn-small" @click="openModal">
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
  const { data } = await ApiService.appointments.getForSubmission(activeSubmission.id)

  if (data) {
    appointments.value = data.data
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
