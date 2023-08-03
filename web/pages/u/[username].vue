<script setup>
import { DynamicForm, AlertWrapper } from '#components'

const route = useRoute()
const { $apiService } = useNuxtApp()

const {
	validationErrs,
	showFormAlert,
	formStatus,
	alertMessage,
	formIsSubmitting,
	handleResponseErrors,
} = useFormErrors()

const { schema } = useSubmissionSchema()

const initialValues = {
	first_name: null,
	last_name: null,
	email: null,
	phone: null,
	idea: null,
}

definePageMeta({
	middleware: 'user-exists',
	layout: 'app',
})

const handleSubmit = async formData => {
	formIsSubmitting.value = true
	try {
		const { username } = route.params
		const res = await $apiService.submissions.store(username, formData)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Request submitted'

		return
	} catch (error) {
		console.log(error.message.response)

		handleResponseErrors(error.message.response)
	} finally {
		formIsSubmitting.value = false
	}
}
</script>

<template>
	<div class="">
		<div class="px-5 md:w-[50%] md:px-0 lg:w-2/5 mx-auto mt-0">
			<header class="pt-10 mb-10">
				<div class="align-center mb-5">
					<h1 class="text-3xl font-bold">Appointment Submission Form</h1>
				</div>

				<AlertWrapper
					v-if="showFormAlert"
					:status="formStatus"
					:msg="alertMessage"
				/>
			</header>

			<DynamicForm
				formId="submission-create"
				:schema="schema"
				:data="initialValues"
				:validationErrs="validationErrs"
				:disabled="formIsSubmitting"
				@form-submitted="handleSubmit"
			/>
		</div>
	</div>
</template>
