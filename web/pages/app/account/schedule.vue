<script setup>
import CalenderScheduleForm from '@/components/forms/CalendarScheduleForm'
</script>

<template>
	<div class="layout-main p-4 w-full">
		<section class="mb-10">
			<header class="mb-5">
				<h2 class="text-xl font-semibold">Update Schedule:</h2>
			</header>
		</section>

		<Card class="w-full">
			<template #content>
				<div class="flex justify-around">
					<CalenderScheduleForm fetchSchedule class="lg:w-2/3 w-full" />
				</div>
			</template>
		</Card>
	</div>
</template>
