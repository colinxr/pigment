<script setup>
import { markRaw } from 'vue'
import { storeToRefs } from 'pinia'
import useModalStore from '@/stores/modal'
import ClientEditModal from '@/components/Modal/ClientEditModal.vue'
import useSubmissionsStore from '@/stores/submissions'

const modalStore = useModalStore()
const submissionsStore = useSubmissionsStore()

const { clearActiveSubmission, toggleActionPane } = submissionsStore

const { showActionPane } = storeToRefs(submissionsStore)

const props = defineProps({
	client: {
		type: Object,
		required: true,
	},
})

const openModal = () => {
	modalStore.openModal({ component: markRaw(ClientEditModal), props })
}
</script>

<template>
	<header
		class="border-gray-100 border-b py-3 px-1 md:px-4 flex justify-between"
	>
		<div>
			<button @click="clearActiveSubmission">
				<i class="pi pi-fw pi-chevron-left"></i>
			</button>
			<span class="font-bold md:text-lg">
				{{ client.full_name }}
			</span>
		</div>
		<!-- <button class="text-sm" @click="openModal">Edit Client</button> -->

		<button @click="toggleActionPane" v-if="!showActionPane" class="self-end">
			<i class="pi pi-fw pi-angle-double-left"></i>
		</button>
	</header>
</template>
