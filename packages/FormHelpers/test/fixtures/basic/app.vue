<script setup>
import { useFormErrors } from '../composables/useFormErrors'

const {
	errorState,
	showFormAlert,
	formStatus,
	alertMessage,
	handleResponseErrors,
} = useFormErrors()

const schema = [
	{
		$el: 'div',
		attrs: {
			class: 'flex gap-4',
		},
		children: [
			{
				$formkit: 'text',
				label: 'First Name',
				name: 'first_name',
				validation: 'required',
				value: 'test',
				validationVisibility: 'dirty',
				outerClass: 'w-1/2',
			},
			{
				$formkit: 'text',
				label: 'Last Name',
				name: 'last_name',
				validation: 'required',
				validationVisibility: 'dirty',
				outerClass: 'w-1/2',
			},
		],
	},

	{
		$el: 'div',
		attrs: {
			class: 'flex gap-4',
		},
		children: [
			{
				$formkit: 'text',
				label: 'Email',
				name: 'email',
				validation: 'required',
				validationVisibility: 'dirty',
				outerClass: 'w-1/2',
			},

			{
				$formkit: 'tel',
				label: 'Phone Number',
				name: 'phone',
				validation: 'matches:/^[0-9]*$/',
				validationMessages: {
					matches: 'Must only have numbers. ',
				},
				validationVisibility: 'dirty',
				outerClass: 'w-1/2',
			},
		],
	},

	{
		$formkit: 'textarea',
		label: 'Message',
		name: 'idea',
		rows: '5',
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

// const handleSubmit = async formData => {
// 	try {
// 		const { username } = route.params
// 		const res = await $apiService.submissions.store(username, formData)

// 		if (res.status !== 201) handleResponseErrors(res)

// 		showFormAlert.value = true
// 		formStatus.value = 'success'
// 		alertMessage.value = res.data.message || 'Request submitted'
// 	} catch (error) {
// 		console.log(error)

// 		if (error.response?.status === 403) return

// 		alertMessage.value = 'Something went wrong'
// 		formStatus.value = 'error'
// 		showFormAlert.value = true
// 	}
// }
</script>

<template>
	<header>
		<AlertWrapper v-if="showFormAlert" :status="formStatus" :msg="''" />
	</header>

	<DynamicForm
		formId="submission-create"
		:schema="schema"
		:data="initialValues"
		:errorState="errorState"
		@form-submitted="handleSubmit"
	/>
</template>
