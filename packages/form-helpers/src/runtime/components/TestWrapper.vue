<script setup>
import useFormErrors from '../composables/useFormErrors'

import AlertWrapper from './Alerts/AlertWrapper.vue'
import DynamicForm from './DynamicForm.vue'

const {
	formStatus,
	validationErrs,
	alertMessage,
	showFormAlert,
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

const handleSubmit = async (formData, resData) => {
	try {
		const res = resData

		formStatus.value = 'success'
		showFormAlert.value = true
		alertMessage.value = res.data.message || 'Request submitted'
	} catch (error) {
		console.log(error.message.response)

		handleResponseErrors(error.message.response)
	}
}
</script>

<template>
	<header>
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
		:disabled="false"
		@form-submitted="handleSubmit"
	/>
</template>
