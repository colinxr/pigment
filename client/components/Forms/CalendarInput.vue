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
