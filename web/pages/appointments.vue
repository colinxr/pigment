<script setup>
  import AppointmentCard from "@/components/Appointments/AppointmentCard.vue"
  import ApiService from "@dayplanner/apiservice"

  const appointments = ref([])

  onBeforeMount(async () => {
    const { data } = await ApiService.appointments.get()

    appointments.value = data.data
  })
  // get a list of upcoming appointments for the user
</script>

<template>
  <div class="grid">
    <div class="col-12 lg-col-6">
      <div class="card">
        <h5>Appointments</h5>
        <div>
          <AppointmentCard
            v-for="(appt, i) in appointments"
            :key="i"
            :appointment="appt"
            class="mb-2"
          />
        </div>
      </div>
    </div>
  </div>
</template>
