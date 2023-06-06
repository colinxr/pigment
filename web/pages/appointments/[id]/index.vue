<script setup>
import ApiService from '@dayplanner/apiservice'
import useAppointmentSchema from '@/composables/useAppointmentSchema'

import DynamicForm from '@/components/Forms/DynamicForm.vue'

const { schema } = useAppointmentSchema()

const route = useRoute()

const isLoading = ref(true)
const appointment = ref({})
const initialValues = {}

onBeforeMount(async () => {
	const { data } = await ApiService.appointments.show(route.params.id)

	appointment.value = data.data

	initialValues.name = data.data.name
	initialValues.description = data.data.description
	initialValues.startDateTime = convertToIsoString(data.data.startDateTime)
	initialValues.duration = getDuration(
		data.data.startDateTime,
		data.data.endDateTime
	)
	initialValues.price = data.data.price
	initialValues.deposit = data.data.deposit

	isLoading.value = false
})
</script>

<template>
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">
			Appointment: {{ appointment.name }}
		</h2>

		<Card class="w-full">
			<template v-if="!isLoading && appointment" #content>
				<DynamicForm
					formId="appointment-view"
					disabled
					:schema="schema"
					:data="initialValues"
					:submitAttrs="{ wrapperClass: 'hidden' }"
				/>
			</template>
		</Card>
	</div>
</template>
