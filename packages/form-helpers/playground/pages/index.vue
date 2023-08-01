<script setup>
const route = useRoute()
const { $apiService } = useNuxtApp()

const {
	showFormAlert,
	formStatus,
	alertMessage,
	validationErrs,
	handleResponseErrors,
} = useFormErrors()

const formSchema = [
	{
		$formkit: 'text',
		label: 'First Name',
		name: 'first_name',
		validation: 'required',
		value: 'test',
		validationVisibility: 'dirty',
	},
	{
		$formkit: 'text',
		label: 'Last Name',
		name: 'last_name',
		validation: 'required',
		validationVisibility: 'dirty',
	},

	{
		$formkit: 'text',
		label: 'Email',
		name: 'email',
		validation: 'required',
		validationVisibility: 'dirty',
	},
]

const initialValues = {
	first_name: null,
	last_name: null,
	email: null,
	phone: null,
	idea: null,
}

const handleSubmit = async () => {
	try {
		const { username } = route.params
		const res = await $apiService.submissions.store(username, formData)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Request submitted'
	} catch (error) {
		console.log(error.message.response)

		handleResponseErrors(error.message.response)
	}
}
</script>

<template>
	<div>
		<header>
			<div class="flex justify-between mb-2">
				<h1>Appointment Submission Form</h1>
			</div>

			<AlertWrapper
				v-if="showFormAlert"
				:status="formStatus"
				:msg="alertMessage"
			/>
		</header>

		<DynamicForm
			formId="client-create"
			:schema="formSchema"
			:data="initialValues"
			:validationErrs="validationErrs"
			:disabled="false"
		/>
	</div>
</template>
