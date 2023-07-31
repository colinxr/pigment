<script setup>
import useAuthStore from '@/stores/auth'
import useRegistrationSchema from '@/composables/useRegistrationSchema'

import { DynamicForm } from '#components'
import { AlertWrapper } from '#components'

const { $apiService } = useNuxtApp()

const authStore = useAuthStore()
const { errorState, handleResponseErrors, showFormAlert } = useFormErrors()
const { schema } = useRegistrationSchema()

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
	try {
		const response = await $apiService.auth.register(formData)

		if (response.status !== 200) {
			return handleResponseErrors(response)
		}
		const { user, token } = response.data

		authStore.setUser({ ...user, token })

		return navigateTo('/calendar')
	} catch (error) {
		console.log(error)
		errorState.isSet = true
		errorState.message = 'Something went wrong'
		return false
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
			:errorState="errorState"
			@form-submitted="handleSubmit"
		/>
	</div>
</template>
