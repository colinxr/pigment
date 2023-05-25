<template>
  hecck ya
</template>

<script setup>
import ApiService from '@dayplanner/apiservice'
import useDashboardStore from '@/stores/dashboard'
// import useModalStore from '@/stores/modal'

const { activeSubmission } = useDashboardStore()
// const modalStore = useModalStore()

const appointments = ref([])

onMounted(async () => {
  const res = await ApiService.submissions.getAppointments(activeSubmission.id)
  console.log(res)
  if (res.data) {
    appointments.value = res.data
  }
})

// const openModal = () => {
//   // modalStore.openModal({ component: ClientEditModal, props })
// }

</script>

<!-- fetch appointments for this submission.  -->

<!-- list of appointments  -->

<!-- create new appointment button  -->

<!-- open appointment edit modal  -->

<!-- edit an appointment  -->
