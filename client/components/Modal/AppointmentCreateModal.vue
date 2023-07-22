<script setup>
import ApiService from '@pigment/api-service'
import useAuthStore from '@/stores/auth'
import useModalStore from '@/stores/modal'
import useFormErrors from '@/composables/useFormErrors'
import { getTimeZoneOffset } from '@/composables/useDateService'
import useAppointmentSchema from '@/composables/useAppointmentSchema'
import useWatchForRefresh from '@/composables/useWatchForRefresh'

import DynamicForm from '@/components/Forms/DynamicForm.vue'
import AlertWrapper from '@/components/Alerts/AlertWrapper.vue'

const route = useRoute()
const authStore = useAuthStore()
const modalStore = useModalStore()

const { appointmentForSubmission } = useAppointmentSchema()

const { triggerRefresh } = useWatchForRefresh()

const {
	errorState,
	showFormAlert,
	formStatus,
	alertMessage,
	handleResponseErrors,
} = useFormErrors()

const props = defineProps({
	submission: {
		type: Object,
		required: true,
	},
})

const initialValues = {
	name: `${props.submission.client.full_name}`,
	description: `${props.submission.idea}`,
	startDateTime: null,
	duration: 1,
	price: null,
	deposit: null,
}

const handleSubmit = async formData => {
	showFormAlert.value = false

	authStore.setLastURL(route)

	try {
		const timezone = getTimeZoneOffset()
		formData.startDateTime = `${formData.startDateTime}:00${timezone}`

		const res = await ApiService.appointments.store(
			formData,
			props.submission.id
		)

		if (res.status !== 200) handleResponseErrors(res)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Appointment created'

		triggerRefresh()

		return
	} catch (error) {
		console.log(error)

		if (error.response?.status === 403) return

		alertMessage.value = 'Something went wrong'
		formStatus.value = 'error'
		showFormAlert.value = true
	}
}
</script>

<template>
	<div
		class="bg-white border border-gray mx-auto w-full max-w-[800px] rounded-xl p-4"
	>
		<header>
			<div class="flex justify-between mb-2">
				<h1>Create Appointment</h1>
				<span class="cursor-pointer" @click="modalStore.closeModal"> X </span>
			</div>

			<AlertWrapper
				v-if="showFormAlert"
				:status="formStatus"
				:msg="alertMessage"
			/>
		</header>

		<DynamicForm
			formId="appointment-create"
			:schema="appointmentForSubmission"
			:data="initialValues"
			:errorState="errorState"
			@form-submitted="handleSubmit"
		/>
	</div>
</template>
