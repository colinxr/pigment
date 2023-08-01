<script setup>
import useClientSchema from '@/composables/useClientSchema'

import { DynamicForm, AlertWrapper } from '#components'

const { $apiService } = useNuxtApp()

const { schema } = useClientSchema()

const route = useRoute()
const {
	showFormAlert,
	formStatus,
	alertMessage,
	validationErrs,
	handleResponseErrors,
} = useFormErrors()

const isLoading = ref(true)
const client = ref({})
const initialValues = {}

onBeforeMount(async () => {
	const { data } = await $apiService.clients.show(route.params.id)

	client.value = data.data

	initialValues.first_name = data.data.first_name
	initialValues.last_name = data.data.last_name
	initialValues.email = data.data.email

	isLoading.value = false
})

const handleSubmit = async formData => {
	showFormAlert.value = false
	try {
		const res = await $apiService.clients.update(client.value.id, formData)

		if (res.status !== 200) handleResponseErrors(res)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Client Updated'
		return
	} catch (error) {
		console.log(error)

		if (error.response?.status === 403) return

		alertMessage.value = 'Something went wrong'
		formStatus.value = 'error'
		showFormAlert.value = true
	}
}

definePageMeta({
	middleware: 'user-is-authenticated',
})
</script>

<template>
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">Edit Client</h2>

		<Card class="w-full">
			<template v-if="!isLoading && client" #content>
				<AlertWrapper
					v-if="showFormAlert"
					:status="formStatus"
					:msg="alertMessage"
				/>

				<DynamicForm
					formId="client-edit"
					:schema="schema"
					:data="initialValues"
					:validationErrs="validationErrs"
					@form-submitted="handleSubmit"
				/>
			</template>
		</Card>
	</div>
</template>
