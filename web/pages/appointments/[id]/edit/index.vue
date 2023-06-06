<script setup>
import ApiService from '@dayplanner/apiservice'
import useFormErrors from '@/composables/useFormErrors'
import DynamicForm from '@/components/Forms/DynamicForm.vue'
import AlertWrapper from '@/components/Alerts/AlertWrapper.vue'

import {
	getTimeZoneOffset,
	convertToIsoString,
	getDuration,
} from '@/services/dateService'

import useAppointmentSchema from '@/composables/useAppointmentSchema'

const { schema } = useAppointmentSchema()

const route = useRoute()
const {
	errorState,
	showFormAlert,
	formStatus,
	alertMessage,
	handleResponseErrors,
} = useFormErrors()

const isLoading = ref(true)
const appointment = ref({})
const initialValues = {}

onBeforeMount(async () => {
	const { data } = await ApiService.appointments.show(route.params.id)

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

		if (res.status !== 200) handleResponseErrors(res)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Appointment Updated'
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
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">Edit Appointment</h2>

		<Card class="w-full">
			<template v-if="!loading && appointment" #content>
				<AlertWrapper
					v-if="showFormAlert"
					:status="formStatus"
					:msg="alertMessage"
				/>

				<DynamicForm
					form-id="appointment-edit"
					:schema="schema"
					:data="initialValues"
					:error-state="errorState"
					@form-submitted="handleSubmit"
				/>
			</template>
		</Card>
	</div>
</template>
