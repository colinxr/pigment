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
	try {
		// const { username } = route.params
		// const res = await $apiService.submissions.store(username, formData)

		// if (res.status !== 201) handleResponseErrors(res)

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
	<div class="">
		<div class="px-5 md:w-[50%] md:px-0 lg:w-2/5 mx-auto mt-0">
			<header class="pt-10 mb-10">
				<div class="align-center">
					<h1 class="text-3xl font-bold">Appointment Submission Form</h1>
				</div>

				<AlertWrapper
					v-if="showFormAlert"
					:status="formStatus"
					:msg="'alertMessage'"
				/>
			</header>

			<DynamicForm
				formId="submission-create"
				:schema="schema"
				:data="initialValues"
				:errorState="[]"
				@form-submitted="handleSubmit"
			/>
		</div>
	</div>
</template>
