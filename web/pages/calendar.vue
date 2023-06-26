<script setup>
import CalendarScheduleForm from '@/components/Forms/CalendarScheduleForm.vue'

definePageMeta({
	middleware: 'user-is-authenticated',
	layout: 'auth',
})
</script>

<template>
	<div class="px-5 md:w-1/2 w-full">
		<CalendarScheduleForm :fetchSchedule="false" />
	</div>
</template>
