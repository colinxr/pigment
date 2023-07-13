<script setup>
import ApiService from '@/services/ApiService'
import { getReadableDate } from '@/composables/useDateService.js'
// import AppointmentCard from '@/components/Appointments/AppointmentCard.vue'
import LoadingCard from '@/components/Appointments/LoadingCard.vue'

const clients = ref([])
const isLoading = ref(true)

const { shouldRefreshData } = useWatchForRefresh()

const fetchData = async () => {
	const { data } = await ApiService.clients.index()

	clients.value = data.data
	isLoading.value = false
}

onBeforeMount(async () => await fetchData())

watch(shouldRefreshData, async () => {
	isLoading.value = true

	await fetchData()
})

/* eslint-disable-next-line */
const handleDelete = ($event, clientID) => {
	const client = clients.value.find(({ id }) => id === clientID)

	modalStore.openModal({
		component: markRaw(AppointmentDeleteModal),
		props: { client },
	})
}

/* eslint-disable-next-line */
const handleEdit = ($event, clientID) => {
	navigateTo(`/clients/${clientID}/edit`)
}

definePageMeta({
	keepalive: true,
	middleware: 'user-is-authenticated',
})
</script>

<template>
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">Clients</h2>

		<Card class="w-full">
			<template v-if="!isLoading && clients.length" #content>
				<DataTable
					v-model:selection="clients"
					:value="clients"
					data-key="id"
					:stripedRows="true"
					responsiveLayout="scroll"
					paginator
					:rows="50"
				>
					<!-- <Column selectionMode="multiple" headerStyle="width: 3em"></Column> -->
					<Column field="id" header="ID">
						<template #body="slotProps">
							<NuxtLink :to="`/app/clients/${slotProps.data.id}`">
								{{ slotProps.data.id }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="full_name" header="Name">
						<template #body="slotProps">
							<NuxtLink :to="`/app/clients/${slotProps.data.id}`">
								{{ slotProps.data.full_name }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="email" header="Email">
						<template #body="slotProps">
							<NuxtLink :to="`/app/clients/${slotProps.data.id}`">
								{{ slotProps.data.email }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="created_at" header="Created At">
						<template #body="slotProps">
							{{ getReadableDate(slotProps.data.created_at) }}
						</template>
					</Column>
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

			<template v-else="!isLoading && !clients.length" #content>
				<div class="w-full md:max-w-1/2">
					<h2>No clients yet.</h2>
				</div>
			</template>

			<template v-else #content>
				<div class="w-full md:max-w-1/2">
					<LoadingCard v-for="i in 3" :key="i" />
				</div>
			</template>
		</Card>
	</div>
</template>
