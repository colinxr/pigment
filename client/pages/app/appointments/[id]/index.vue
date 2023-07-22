<script setup>
import useAppointmentSchema from '@/composables/useAppointmentSchema'

import DynamicForm from '@/components/Forms/DynamicForm.vue'

const { $apiService } = useNuxtApp()

const { appointmentForSubmission } = useAppointmentSchema()

const route = useRoute()

const isLoading = ref(true)
const appointment = ref({})
const initialValues = {}

onBeforeMount(async () => {
	const { data } = await $apiService.appointments.show(route.params.id)

	if (data.status === 404) return redirect('/not-found')

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

definePageMeta({
	middleware: 'user-is-authenticated',
})
</script>

<template>
	<div class="layout-main p-4 w-full">
		<header class="mb-5">
			<h2 class="text-xl font-semibold">Appointment: {{ appointment.name }}</h2>
			<nuxt-link :to="`/app/appointments/${route.params.id}/edit`">
				Edit
			</nuxt-link>
		</header>

		<Card class="w-full">
			<template v-if="!isLoading && appointment" #content>
				<DynamicForm
					formId="appointment-view"
					:disabled="true"
					:schema="appointmentForSubmission"
					:data="initialValues"
					:submitAttrs="{ wrapperClass: 'hidden' }"
				/>
			</template>
		</Card>
	</div>
</template>
