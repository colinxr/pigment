<script setup>
  import AppointmentCard from "@/components/Appointments/AppointmentCard.vue"
  import ApiService from "@dayplanner/apiservice"

  const appointments = ref([])

  onBeforeMount(async () => {
    const { data } = await ApiService.appointments.get()

    appointments.value = data.data
  })
  // get a list of upcoming appointments for the user

  definePageMeta({
    keepalive: true,
  })
</script>

<template>
  <div class="p-4 w-full">
    <Card class="w-full">
      <template #header class="p-4">
        <h2>Appointments</h2>
      </template>

      <template #content>
        <AppointmentCard
          v-for="(appt, i) in appointments"
          :key="i"
          :appointment="appt"
          class="mb-2"
        />
      </template>
    </Card>
  </div>
</template>
