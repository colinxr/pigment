<script setup>
import Calendar from 'primevue/calendar'

const props = defineProps({
	context: {
		type: Object,
		required: true,
	},
})

const startTime = ref('')

watch(startTime, newVal => {
	props.context.node.input(newVal)
})

onMounted(() => {
	const dateTime = new Date(props.context.value + 'Z')

	const dd = dateTime.getDate().toString().padStart(2, '0')
	const mm = (dateTime.getUTCMonth() + 1).toString().padStart(2, '0')
	const yy = dateTime.getFullYear()

	const hours = dateTime.getHours().toString().padStart(2, '0')
	const minutes = dateTime.getMinutes().toString().padStart(2, '0')

	startTime.value = `${dd}/${mm}/${yy} ${hours}:${minutes}`
})
</script>

<template>
	<Calendar
		class="!flex"
		v-model="startTime"
		showTime
		hourFormat="12"
		touchUI
		dateFormat="dd/mm/yy"
	/>
</template>
