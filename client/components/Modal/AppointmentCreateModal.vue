<script setup>
import useAuthStore from '@/stores/auth'
import useModalStore from '@/stores/modal'

import { DynamicForm } from '#components'
import { AlertWrapper } from '#components'

import {
	getTimeZoneOffset,
	convertToIsoString,
} from '@/composables/useDateService'
import useAppointmentSchema from '@/composables/useAppointmentSchema'
import useWatchForRefresh from '@/composables/useWatchForRefresh'

const { $apiService } = useNuxtApp()

const route = useRoute()
const authStore = useAuthStore()
const modalStore = useModalStore()

const { appointmentForSubmission } = useAppointmentSchema()

const { triggerRefresh } = useWatchForRefresh()

const {
	showFormAlert,
	formStatus,
	alertMessage,
	validationErrs,
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
		// const timezone = getTimeZoneOffset()
		const isoString = convertToIsoString(formData.startDateTime)
		console.log(isoString)
		formData.startDateTime = isoString

		const res = await $apiService.appointments.store(
			formData,
			props.submission.id
		)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Appointment created'

		triggerRefresh()

		return
	} catch (error) {
		console.log(error.message.response)

		handleResponseErrors(error.message.response)
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
			:validationErrs="validationErrs"
			@form-submitted="handleSubmit"
		/>
	</div>
</template>
