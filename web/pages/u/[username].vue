<script setup>
const route = useRoute()
const { $apiService } = useNuxtApp()

const {
	errorState,
	showFormAlert,
	formStatus,
	alertMessage,
	handleResponseErrors,
} = useFormErrors()

const formSchema = useSubmissionSchema()

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

const handleSubmit = async () => {
	try {
		const { username } = route.params
		const res = await $apiService.submissions.store(username, formData)

		if (res.status !== 200) handleResponseErrors(res)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Request submitted'

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
	<div>
		<header>
			<div class="flex justify-between mb-2">
				<h1>Appointment Submission Form</h1>
			</div>

			<AlertWrapper v-if="showFormAlert" :status="true" :msg="'alertMessage'" />
		</header>

		<DynamicForm
			formId="submission-create"
			:schema="formSchema"
			:data="initialValues"
			:errorState="[]"
			@form-submitted="handleSubmit"
		/>
	</div>
</template>
