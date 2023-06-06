<script setup>
import { computed } from 'vue'
import useModalStore from '@/stores/modal'
import { getReadableDate, dateIsUpcoming } from '@/composables/useDateService'
import AppointmentDeleteModal from '@/components/Modal/AppointmentDeleteModal.vue'

const modalStore = useModalStore()

const props = defineProps({
	appointment: {
		type: Object,
		required: true,
	},
	size: {
		type: String,
		default: 'small',
	},
})

const showControls = ref(false)

const dateTime = computed(() =>
	getReadableDate(props.appointment.startDateTime)
)

const status = computed(() =>
	dateIsUpcoming(props.appointment.startDateTime) ? 'active' : 'past'
)

const handleDelete = () => {
	modalStore.openModal({
		component: markRaw(AppointmentDeleteModal),
		props: { appointment: props.appointment },
	})
}

const handleEdit = () =>
	navigateTo(`/appointments/${props.appointment.id}/edit`)

const handleMouseHover = () => {
	if (status.value === 'past') {
		showControls.value = false
		return
	}

	showControls.value = !showControls.value
}
</script>

<template>
	<nuxt-link :to="`/appointments/${props.appointment.id}/`">
		<div
			class="appt-card relative mb-2"
			:class="props.size !== 'large' ? 'text-xs' : 'text-md'"
			@mouseenter="handleMouseHover"
			@mouseleave="handleMouseHover"
		>
			<div
				class="font-medium"
				:class="[
					status !== 'active' ? 'line-through' : '',
					props.size !== 'large' ? 'text-sm' : 'text-md',
				]"
			>
				{{ dateTime }}
			</div>
			<div class="truncate w-40">
				{{ props.appointment.name }}
			</div>
			<div class="truncate w-40">
				{{ props.appointment.description }}
			</div>

			<div v-if="showControls" class="controls absolute right-0 top-0 p-1">
				<button class="mr-2" @click.prevent="handleEdit">
					<i class="pi pi-pencil" />
				</button>

				<button @click.prevent="handleDelete">
					<i class="pi pi-times" />
				</button>
			</div>
		</div>
	</nuxt-link>
</template>
