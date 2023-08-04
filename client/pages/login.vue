<script setup>
import useAuthStore from '@/stores/auth'
import { DynamicForm, AlertWrapper } from '#components'

const { $apiService } = useNuxtApp()

const { schema } = useLoginSchema()

const store = useAuthStore()

const {
	showFormAlert,
	alertMessage,
	formStatus,
	validationErrs,
	formIsSubmitting,
	handleResponseErrors,
} = useFormErrors()

definePageMeta({
	middleware: 'user-is-authenticated',
	layout: 'auth',
})

const initialValues = {
	email: '',
	password: '',
}

const handleSubmit = async formData => {
	formIsSubmitting.value = true

	try {
		const response = await $apiService.auth.login(formData)

		console.log(response)

		if (response.status !== 200) {
			return
		}
		const { user, token } = response.data

		store.setUser({ ...user, token })

		return navigateTo('/')
	} catch (error) {
		console.log(error.message.response)

		handleResponseErrors(error.message.response)

		// alertMessage.value = 'something went wrong'
		// formStatus.value = 'error'
		// showFormAlert.value = true
	} finally {
		formIsSubmitting.value = false
	}
}
</script>

<template>
	<div class="w-full md:w-2/5 lg:w-1/4 px-5 md:px-0">
		<header>
			<div class="mb-5">
				<h1 class="text-3xl font-bold">Login</h1>
			</div>

			<AlertWrapper
				v-if="showFormAlert"
				:status="formStatus"
				:msg="alertMessage"
			/>
		</header>

		<DynamicForm
			formId="client-edit"
			:schema="schema"
			:data="initialValues"
			:validationErrs="validationErrs"
			:disabled="formIsSubmitting"
			@form-submitted="handleSubmit"
		/>
	</div>
</template>
