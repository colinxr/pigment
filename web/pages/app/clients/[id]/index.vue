<script setup>
import ApiService from '@dayplanner/apiservice'
import useClientSchema from '@/composables/useClientSchema'

import DynamicForm from '@/components/Forms/DynamicForm.vue'

const { schema } = useClientSchema()

const route = useRoute()

const isLoading = ref(true)
const client = ref({})
const clientValues = {}

const appointments = ref({})

onBeforeMount(async () => {
	const { data } = await ApiService.clients.show(route.params.id)
	client.value = data.data

	clientValues.first_name = data.data.first_name
	clientValues.last_name = data.data.last_name
	clientValues.email = data.data.email

	isLoading.value = false
})

onMounted(async () => {
	const { data: appts } = await ApiService.appointments.index(
		'client_id',
		route.params.id
	)

	appointments.value = appts.data
})

definePageMeta({
	keepalive: true,
	middleware: 'user-is-authenticated',
})
</script>

<template>
	<div class="layout-main p-4 w-full">
		<section class="mb-10">
			<header class="mb-5">
				<h2 class="text-xl font-semibold">
					Client:
					<span v-if="client">{{ client.full_name }}</span>
				</h2>
				<nuxt-link :to="`/clients/${route.params.id}/edit`"> Edit </nuxt-link>
			</header>

			<Card class="w-full">
				<template v-if="!isLoading && client" #content>
					<DynamicForm
						formId="client-edit"
						disabled
						:schema="schema"
						:data="clientValues"
						:submitAttrs="{ wrapperClass: 'hidden' }"
					/>
				</template>
			</Card>
		</section>
		<section>
			<h2 class="text-xl font-semibold mb-5">
				Appointments for:
				<span v-if="client">{{ client.full_name }}</span>
			</h2>

			<Card class="w-full">
				<template #content>
					<DataTable
						v-if="appointments.length"
						v-model:selection="appointments"
						:value="appointments"
						data-key="id"
						:stripedRows="true"
						responsiveLayout="scroll"
						paginator
						:rows="50"
					>
						<Column field="startDateTime" header="Date" sortable>
							<template #body="slotProps">
								<NuxtLink :to="`/appointments/${slotProps.data.id}`">
									{{ getReadableDate(slotProps.data.startDateTime) }}
								</NuxtLink>
							</template>
						</Column>
						<Column field="description" header="Description">
							<template #body="slotProps">
								<NuxtLink :to="`/appointments/${slotProps.data.id}`">
									{{ slotProps.data.description }}
								</NuxtLink>
							</template>
						</Column>
						<Column field="endDateTime" header="Duration">
							<template #body="slotProps">
								{{
									getDuration(
										slotProps.data.startDateTime,
										slotProps.data.endDateTime
									)
								}}
							</template>
						</Column>
						<Column field="price" header="Price" />
						<Column field="deposit" header="Deposit" />
						<Column field="edit_edit" header="Edit/Delete">
							<template #body="slotProps">
								<div class="controls flex justify-around">
									<button
										@click.prevent="handleEdit($event, slotProps.data.id)"
									>
										<i class="pi pi-pencil" />
									</button>

									<button @click.prevent="handleDelete">
										<i class="pi pi-times" />
									</button>
								</div>
							</template>
						</Column>
					</DataTable>

					<h4 v-else>No Appointments Booked</h4>
				</template>
			</Card>
		</section>
	</div>
</template>
