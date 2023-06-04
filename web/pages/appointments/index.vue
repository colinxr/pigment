<script setup>
  import AppointmentCard from "@/components/Appointments/AppointmentCard.vue"
  import ApiService from "@dayplanner/apiservice"

  const appointments = ref([])

  onBeforeMount(async () => {
    const { data } = await ApiService.appointments.get()

    appointments.value = data.data
  })

  definePageMeta({
    keepalive: true,
  })
</script>

<template>
  <div class="layout-main p-4 w-full">
    <h2 class="text-xl font-semibold mb-5">Appointments</h2>

    <Card class="w-full">
      <template #content>
        <AppointmentCard
          v-for="(appt, i) in appointments"
          :key="i"
          :appointment="appt"
          class="mb-5"
        />
      </template>
    </Card>
  </div>
</template>
