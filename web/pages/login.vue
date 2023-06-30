<script setup>
import ApiService from '@/services/ApiService'
import useAuthStore from '@/stores/auth'
import TextInput from '@/components/Forms/TextInput.vue'

const store = useAuthStore()
const { errorState, handleResponseErrors } = useFormErrors()

definePageMeta({
	middleware: 'user-is-authenticated',
	layout: 'auth',
})

const email = ref('')
const password = ref('')

const handleSubmit = async () => {
	try {
		const response = await ApiService.auth.login({
			email: email.value,
			password: password.value,
		})

		if (response.status !== 200) {
			return handleResponseErrors(response)
		}
		const { user, token } = response.data

		store.setUser({ ...user, token })

		return navigateTo('/app')
	} catch (error) {
		console.log(error)
		errorState.isSet = true
		errorState.message = 'Something went wrong'
		return false
	}
}
</script>

<template>
	<form class="w-1/4" @submit.prevent="handleSubmit">
		<div class="form-control mb-5">
			<TextInput id="email" v-model="email" labelText="Email" />
		</div>

		<div class="form-control mb-5">
			<TextInput
				id="password"
				v-model="password"
				labelText="Password"
				fieldType="password"
			/>
		</div>

		<button
			class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none"
			type="submit"
		>
			Login
		</button>

		<div
			v-if="errorState.isSet"
			class="alert alert-error shadow-lg w-full max-w-xs mt-5"
		>
			<div>
				<span>{{ errorState.message }}</span>
			</div>
		</div>
	</form>
</template>
