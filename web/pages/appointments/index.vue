<script setup>
import ApiService from '@dayplanner/apiservice'
import useModalStore from '@/stores/modal'
import AppointmentDeleteModal from '@/components/Modal/AppointmentDeleteModal.vue'
import LoadingCard from '@/components/Appointments/LoadingCard.vue'

const modalStore = useModalStore()

const appointments = ref([])
const isLoading = ref(true)

const { shouldRefreshData } = useWatchForRefresh()

const fetchData = async () => {
	const { data } = await ApiService.appointments.index()

	appointments.value = data.data
	isLoading.value = false
}

onBeforeMount(async () => await fetchData())

watch(shouldRefreshData, async () => {
	isLoading.value = true

	await fetchData()
})

/* eslint-disable-next-line */
const handleDelete = ($event, apptID) => {
	const appointment = appointments.value.find(({ id }) => id === apptID)

	modalStore.openModal({
		component: markRaw(AppointmentDeleteModal),
		props: { appointment },
	})
}

/* eslint-disable-next-line */
const handleEdit = ($event, apptID) => {
	navigateTo(`/appointments/${apptID}/edit`)
}

definePageMeta({
	keepalive: true,
})
</script>

<template>
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">Appointments</h2>

		<Card class="w-full">
			<template v-if="!isLoading && appointments.length" #content>
				<DataTable
					v-model:selection="appointments"
					:value="appointments"
					data-key="id"
					stripedRows="true"
					responsiveLayout="scroll"
					paginator
					:rows="50"
				>
					<!-- <Column selectionMode="multiple" headerStyle="width: 3em"></Column> -->
					<Column field="id" header="ID" sortable>
						<template #body="slotProps">
							<NuxtLink :to="`/appointments/${slotProps.data.id}`">
								{{ slotProps.data.id }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="name" header="Name">
						<template #body="slotProps">
							<NuxtLink :to="`/appointments/${slotProps.data.id}`">
								{{ slotProps.data.name }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="description" header="description">
						<template #body="slotProps">
							<NuxtLink :to="`/appointments/${slotProps.data.id}`">
								{{ slotProps.data.description }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="startDateTime" header="Date" sortable>
						<template #body="slotProps">
							{{ getReadableDate(slotProps.data.startDateTime) }}
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
								<button @click.prevent="handleEdit($event, slotProps.data.id)">
									<i class="pi pi-pencil" />
								</button>

								<button @click.prevent="handleDelete">
									<i class="pi pi-times" />
								</button>
							</div>
						</template>
					</Column>
				</DataTable>
			</template>

			<template v-else #content>
				<div class="w-full md:max-w-1/2">
					<LoadingCard v-for="i in 3" :key="i" />
				</div>
			</template>
		</Card>
	</div>
</template>
