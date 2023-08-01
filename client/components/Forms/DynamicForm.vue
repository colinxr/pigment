<script setup>
import { ref } from 'vue'
import { FormKit, FormKitSchema, setErrors } from '@formkit/vue'

const emit = defineEmits(['form-submitted'])

const props = defineProps({
	formId: {
		type: String,
		required: true,
	},
	schema: {
		type: Object,
		required: true,
	},
	data: {
		type: Object,
		required: true,
	},
	successMessage: {
		type: String,
		default: '',
	},
	validationErrs: {
		type: Object,
		default: () => {},
	},
	disabled: {
		type: Boolean,
		default: false,
	},
	modelToUpdate: {
		type: Object,
		default: () => {},
	},
})

const form = ref({})

const setInitialFormValues = (schema, data) => {
	const newForm = {}
	schema
		.map(buildSchemaFields)
		.filter(v => v !== null || v !== 'slots')
		.flat()
		.forEach(field => {
			newForm[field] = data[field]
		})

	return newForm
}

const buildSchemaFields = field => {
	if (!field.$el) return field.name

	if (field.$el && field.children) {
		const children = field.children.map(buildSchemaFields)

		return children.flat()
	}

	return null
}

watch(
	() => ({ schema: props.schema, data: props.data }),
	({ schema, data }) => {
		form.value = setInitialFormValues(schema, data)
	},
	{ immediate: true }
)

// watch(
// 	() => props.validationErrs,
// 	newErrs => {
// 		setErrors(props.formId, [props.errors.message], newErrs)
// 	}
// )

watch(
	() => props.modelToUpdate,
	({ modelKey, value }) => (form.value[modelKey] = value)
)

const submitHandler = values => emit('form-submitted', values)
</script>

<template>
	<FormKit
		:id="formId"
		v-model="form"
		type="form"
		:disabled="props.disabled"
		:errors="validationErrs"
		:submitAttrs="{
			inputClass:
				'p-button p-component form__submit flex align-center justify-center',
			wrapperClass: 'bg-[#6A64F1]',
		}"
		@submit="submitHandler"
	>
		<FormKitSchema :schema="schema" :data="form" />
	</FormKit>
</template>

<style>
.form__submit {
	background-color: inherit !important;
}
</style>
