<script setup>
import { DynamicForm, AlertWrapper } from '#components'

import {
	getTimeZoneOffset,
	convertToIsoString,
	getDuration,
} from '@/composables/useDateService.js'

import useAppointmentSchema from '@/composables/useAppointmentSchema'

const { $apiService } = useNuxtApp()

const { appointmentForSubmission } = useAppointmentSchema()

const route = useRoute()
const {
	showFormAlert,
	formStatus,
	alertMessage,
	validationErrs,
	formIsSubmitting,
	handleResponseErrors,
} = useFormErrors()

const isLoading = ref(true)
const appointment = ref({})
const initialValues = {}

onBeforeMount(async () => {
	const { data } = await $apiService.appointments.show(route.params.id)

	appointment.value = data.data

	initialValues.name = data.data.name
	initialValues.description = data.data.description
	initialValues.startDateTime = convertToIsoString(data.data.startDateTime)
	initialValues.duration = getDuration(
		data.data.startDateTime,
		data.data.endDateTime
	)
	initialValues.price = data.data.price
	initialValues.deposit = data.data.deposit

	isLoading.value = false
})

const handleSubmit = async formData => {
	formIsSubmitting.value = true
	showFormAlert.value = false
	try {
		console.log(formData.startDateTime)

		const timezone = getTimeZoneOffset()
		formData.startDateTime = `${formData.startDateTime}:00${timezone}`

		console.log(formData.startDateTime)

		const res = await ApiService.appointments.update(
			appointment.value.id,
			formData
		)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Appointment Updated'
		return
	} catch (error) {
		console.log(error.message.response)
		handleResponseErrors(error.message.response)
	} finally {
		formIsSubmitting.value = false
	}
}

definePageMeta({
	middleware: 'user-is-authenticated',
})
</script>

<template>
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">Edit Appointment</h2>

		<Card class="w-full">
			<template v-if="!isLoading && appointment" #content>
				<AlertWrapper
					v-if="showFormAlert"
					:status="formStatus"
					:msg="alertMessage"
				/>

				<DynamicForm
					formId="appointment-edit"
					:schema="appointmentForSubmission"
					:data="initialValues"
					:validationErrs="validationErrs"
					:disabled="formIsSubmitting"
					@form-submitted="handleSubmit"
				/>
			</template>
		</Card>
	</div>
</template>
