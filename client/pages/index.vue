<script setup>

const { alertMessage } = useFormErrors()

console.log(useFormErrors())


definePageMeta({
	middleware: 'user-is-authenticated',
	layout: 'home',
})
</script>

<template>
	<div>hello</div>
</template>
