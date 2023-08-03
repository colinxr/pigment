<script setup>
import useAuthStore from '@/stores/auth'
import useRegistrationSchema from '@/composables/useRegistrationSchema'

import { DynamicForm, AlertWrapper } from '#components'

const { $apiService } = useNuxtApp()

const authStore = useAuthStore()
const { schema } = useRegistrationSchema()

const {
	showFormAlert,
	formStatus,
	alertMessage,
	validationErrs,
	formIsSubmitting,
	handleResponseErrors,
} = useFormErrors()

definePageMeta({
	middleware: 'user-is-authenticated',
	layout: 'auth',
})

const initialValues = {
	first_name: '',
	last_name: '',
	email: '',
	password: '',
	password_confirm: '',
}

const handleSubmit = async formData => {
	formIsSubmitting.value = true
	try {
		const response = await $apiService.auth.register(formData)

		const { user, token } = response.data

		authStore.setUser({ ...user, token })

		return navigateTo('/calendar')
	} catch (error) {
		console.log(error.message.response)

		handleResponseErrors(error.message.response)
	} finally {
		formIsSubmitting.value = false
	}
}
</script>

<template>
	<div class="px-5 mt-5 md:w-1/2 w-full">
		<header>
			<div class="flex justify-between mb-5">
				<h1 class="text-3xl font-bold">Register</h1>
			</div>

			<AlertWrapper
				v-if="showFormAlert"
				:status="formStatus"
				:msg="alertMessage"
			/>
		</header>

		<DynamicForm
			formId="registration"
			:schema="schema"
			:data="initialValues"
			:validationErrs="validationErrs"
			:disabled="formIsSubmitting"
			@form-submitted="handleSubmit"
		/>
	</div>
</template>
