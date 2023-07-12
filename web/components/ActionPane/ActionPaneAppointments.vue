<script setup>
import { markRaw } from 'vue'
import ApiService from '@/services/ApiService'
import useSubmissionsStore from '@/stores/submissions'
import useModalStore from '@/stores/modal'
import useWatchForRefresh from '@/composables/useWatchForRefresh'

import AppointmentCard from '@/components/Appointments/AppointmentCard.vue'
import LoadingCard from '@/components/Appointments/LoadingCard.vue'
import AppointmentCreateModal from '@/components/Modal/AppointmentCreateModal.vue'

const modalStore = useModalStore()
const { shouldRefreshData } = useWatchForRefresh()

const submission = inject('submission')

const isLoading = ref(true)
const appointments = ref([])
const pastAppointments = ref([])

const fetchData = async sub => {
	if (!sub) return null
	const subId = sub.value?.id ?? sub.id

	const { data } = await ApiService.appointments.index('submission_id', subId)

	if (!data) return

	appointments.value = sanitizeResponseData(data.data.upcoming)
	pastAppointments.value = sanitizeResponseData(data.data.past)
	isLoading.value = false
	shouldRefreshData.value = false
}

const clearState = () => {
	isLoading.value = true
	appointments.value = []
	pastAppointments.value = []
}

onMounted(async () => await fetchData(submission))

watch([shouldRefreshData, submission], async ([shouldRefresh, submission]) => {
	clearState()
	await fetchData(submission)
})

const openModal = () => {
	modalStore.openModal({
		component: markRaw(AppointmentCreateModal),
		props: { submission },
	})
}

const sanitizeResponseData = data => Object.values(data)
</script>

<template>
	<div>
		<div v-if="isLoading && !appointments.length" class="mb-5">
			<LoadingCard v-for="i in 3" :key="i" />
		</div>

		<div
			v-if="!isLoading && (appointments.length || pastAppointments.length)"
			class="mb-5"
		>
			<h5 v-if="appointments.length">Upcoming</h5>
			<AppointmentCard
				v-for="(appt, i) in appointments"
				:key="i"
				:appointment="appt"
				class="mb-2"
			/>

			<h5 v-if="pastAppointments.length">Past</h5>
			<AppointmentCard
				v-for="(appt, i) in pastAppointments"
				:key="i"
				:appointment="appt"
				class="mb-2"
			/>
		</div>

		<Button label="Add Appointment" @click="openModal" />
	</div>
</template>
