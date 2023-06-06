<script setup>
import ApiService from '@dayplanner/apiservice'
import useFormErrors from '@/composables/useFormErrors'
import useClientSchema from '@/composables/useClientSchema'

import DynamicForm from '@/components/Forms/DynamicForm.vue'
import AlertWrapper from '@/components/Alerts/AlertWrapper.vue'

const { schema } = useClientSchema()

const route = useRoute()
const {
	errorState,
	showFormAlert,
	formStatus,
	alertMessage,
	handleResponseErrors,
} = useFormErrors()

const isLoading = ref(true)
const client = ref({})
const initialValues = {}

onBeforeMount(async () => {
	const { data } = await ApiService.clients.show(route.params.id)

	client.value = data.data

	initialValues.first_name = data.data.first_name
	initialValues.last_name = data.data.last_name
	initialValues.email = data.data.email

	isLoading.value = false
})

const handleSubmit = async formData => {
	showFormAlert.value = false
	try {
		const res = await ApiService.clients.update(client.value.id, formData)

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
					form-id="client-edit"
					:schema="schema"
					:data="initialValues"
					:error-state="errorState"
					@form-submitted="handleSubmit"
				/>
			</template>
		</Card>
	</div>
</template>
