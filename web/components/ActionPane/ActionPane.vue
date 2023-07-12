<script setup>
import { ref } from 'vue'
import { storeToRefs } from 'pinia'
import ActionPaneAppointments from './ActionPaneAppointments.vue'
import useSubmissionsStore from '@/stores/submissions'

const submissionsStore = useSubmissionsStore()

const { toggleActionPane } = submissionsStore
const { showActionPane } = storeToRefs(submissionsStore)

const currentView = ref('appointments')
</script>

<template>
	<aside
		class="p-4 bg-slate-100 border-gray-200 border-l absolute top-0 right-0 left-0 bottom-0 w-full h-full transform md:transform-x-none md:w-0 z-50"
		:class="{
			'translate-x-full': !showActionPane,
			'translate-x-0 w-[88vw] md:w-1/3 md:relative': showActionPane,
		}"
	>
		<header class="flex justify-end absolute top-6 right-3">
			<span @click="toggleActionPane">Close</span>
		</header>

		<div class="h-full">
			<nav class="flex space-x-2" aria-label="Tabs" role="tablist">
				<button
					id="tabs-with-underline-item-3"
					type="button"
					class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600"
					data-hs-tab="#tabs-with-underline-3"
					aria-controls="tabs-with-underline-3"
					role="tab"
					@click="currentView = 'appointments'"
				>
					Appointments
				</button>

				<button
					id="tabs-with-underline-item-2"
					type="button"
					class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600"
					data-hs-tab="#tabs-with-underline-2"
					aria-controls="tabs-with-underline-2"
					role="tab"
					@click="currentView = 'images'"
				>
					Images
				</button>
			</nav>

			<div class="mt-3">
				<ActionPaneAppointments v-if="currentView === 'appointments'" />
			</div>
		</div>
	</aside>
</template>
