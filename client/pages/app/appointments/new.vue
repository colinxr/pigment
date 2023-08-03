<script setup>
import { getTimeZoneOffset } from '@/composables/useDateService'
import useAppointmentSchema from '@/composables/useAppointmentSchema'

import { DynamicForm, AlertWrapper } from '#components'

const { $apiService } = useNuxtApp()

const { addPropsToSchema, startDateTime } = useAppointmentSchema()

const {
	showFormAlert,
	formStatus,
	alertMessage,
	validationErrs,
	formIsSubmitting,
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
const schema = ref([])
const modelToUpdate = ref({})

onMounted(async () => {
	const { data } = await $apiService.clients.index()

	const clientNames = data.data

	schema.value = addPropsToSchema('newAppointment', {
		client: {
			list: clientNames,
			keysToSearch: ['full_name', 'email'],
			valueToShow: 'email',
		},
	})
})

watch(startDateTime, newVal => {
	console.log(newVal)

	modelToUpdate.value = {
		modelKey: 'startDateTime',
		value: newVal,
	}

	console.log(modelToUpdate.value)
})

const handleSubmit = async formData => {
	formIsSubmitting.value = true
	showFormAlert.value = false

	try {
		const timezone = getTimeZoneOffset()
		formData.startDateTime = `${formData.startDateTime}:00${timezone}`

		const res = await ApiService.appointments.store(formData)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Appointment created'

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
		<h2 class="text-xl font-semibold mb-5">New Appointment</h2>

		<Card class="w-full">
			<template #content>
				<AlertWrapper
					v-if="showFormAlert"
					:status="formStatus"
					:msg="alertMessage"
				/>

				<DynamicForm
					v-if="schema.length"
					formId="appointment-create"
					:schema="schema"
					:data="initialValues"
					:validationErrs="validationErrs"
					:modelToUpdate="modelToUpdate"
					:disabled="formIsSubmitting"
					@form-submitted="handleSubmit"
				/>
			</template>
		</Card>
	</div>
</template>
