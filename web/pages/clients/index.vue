<script setup>
import ApiService from '@dayplanner/apiservice'
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

definePageMeta({ keepalive: true })
</script>

<template>
	<div class="layout-main p-4 w-full">
		<h2 class="text-xl font-semibold mb-5">Clients</h2>

		<Card class="w-full">
			<template #content v-if="!isLoading && clients.length">
				<DataTable
					:value="clients"
					v-model:selection="clients"
					dataKey="id"
					responsiveLayout="scroll"
				>
					<!-- <Column selectionMode="multiple" headerStyle="width: 3em"></Column> -->
					<Column field="id" header="ID">
						<template #body="slotProps">
							<NuxtLink :to="`/clients/${slotProps.data.id}`">
								{{ slotProps.data.id }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="full_name" header="Name">
						<template #body="slotProps">
							<NuxtLink :to="`/clients/${slotProps.data.id}`">
								{{ slotProps.data.full_name }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="email" header="Email">
						<template #body="slotProps">
							<NuxtLink :to="`/clients/${slotProps.data.id}`">
								{{ slotProps.data.email }}
							</NuxtLink>
						</template>
					</Column>
					<Column field="created_at" header="Created At">
						<template #body="slotProps">
							{{ getReadableDate(slotProps.data.created_at) }}
						</template>
					</Column>
					<!-- <Column field="category" header="Category"></Column> -->
					<!-- <Column field="quantity" header="Quantity"></Column> -->
				</DataTable>
				<!-- <AppointmentCard
					v-for="(appt, i) in appointments"
					:key="i"
					:appointment="appt"
					size="large"
					class="mb-5"
				/> -->
			</template>

			<template #content v-else>
				<div class="w-full md:max-w-1/2">
					<LoadingCard v-for="i in 3" :key="i" />
				</div>
			</template>
		</Card>
	</div>
</template>
