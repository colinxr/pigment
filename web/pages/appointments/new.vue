<script setup>
import ApiService from '@dayplanner/apiservice'
import useFormErrors from '@/composables/useFormErrors'
import { getTimeZoneOffset } from '@/composables/useDateService'
import useAppointmentSchema from '@/composables/useAppointmentSchema'

import DynamicForm from '@/components/Forms/DynamicForm.vue'
import AlertWrapper from '@/components/Alerts/AlertWrapper.vue'

const { schema } = useAppointmentSchema()

const {
	errorState,
	showFormAlert,
	formStatus,
	alertMessage,
	handleResponseErrors,
} = useFormErrors()

const initialValues = {
	name: '',
	description: '',
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
			props.submission.id,
			formData
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

		alertMessage.value = 'something went wrong'
		formStatus.value = 'error'
		showFormAlert.value = true
	}
}
</script>

<template>
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">New Appointment</h2>

		<Card class="w-full">
			<template #content>
				<AlertWrapper
					v-if="showFormAlert"
					:status="formStatus"
					:msg="alertMessage"
				/>

				<DynamicForm
					formId="appointment-create"
					:schema="schema"
					:data="initialValues"
					:errorState="errorState"
					:disabled="true"
					@form-submitted="handleSubmit"
				/>
			</template>
		</Card>
	</div>
</template>
