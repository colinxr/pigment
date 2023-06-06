<script setup>
import ApiService from '@dayplanner/apiservice'
import AppointmentCard from '@/components/Appointments/AppointmentCard.vue'
import LoadingCard from '@/components/Appointments/LoadingCard.vue'

const appointments = ref([])
const isLoading = ref(true)

const { shouldRefreshData } = useWatchForRefresh()

const fetchData = async () => {
	const { data } = await ApiService.appointments.index()

	appointments.value = data.data
	isLoading.value = false
}

onBeforeMount(async () => await fetchData())

watch(shouldRefreshData, async () => {
	loading.value = true

	await fetchData()
})

definePageMeta({
	keepalive: true,
})
</script>

<template>
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">Appointments</h2>

		<Card class="w-full">
			<template #content v-if="!loading && appointments.length">
				<AppointmentCard
					v-for="(appt, i) in appointments"
					:key="i"
					:appointment="appt"
					size="large"
					class="mb-5"
				/>
			</template>

			<template #content v-else>
				<div class="w-full md:max-w-1/2">
					<LoadingCard v-for="i in 3" :key="i" />
				</div>
			</template>
		</Card>
	</div>
</template>
