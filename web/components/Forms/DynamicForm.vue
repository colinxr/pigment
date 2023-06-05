<template>
	<FormKit
		:id="formId"
		v-model="form"
		type="form"
		@submit="submitHandler"
		:submitAttrs="{
			inputClass:
				'p-button p-component form__submit flex align-center justify-center',
			wrapperClass: 'bg-[#6A64F1]',
		}"
	>
		<FormKitSchema :schema="schema" :data="form" />
	</FormKit>
</template>

<script setup>
import { ref } from 'vue'
import { FormKitSchema, setErrors } from '@formkit/vue'
import useFormErrors from '@/composables/useFormErrors'

const { buildFormErrorBag } = useFormErrors()

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
	errorState: {
		type: Object,
		default: () => {},
	},
})

const form = ref({})

const setInitialFormValues = (schema, data) => {
	const newForm = {}

	console.log(data)
	schema
		.map(buildSchemaFields)
		.filter(v => v !== null || v !== 'slots')
		.flat()
		.forEach(field => {
			newForm[field] = data[field]
		})

	console.log(newForm)

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

watch(
	() => props.errorState,
	newErrorState => {
		const errorBag = buildFormErrorBag(newErrorState.validationErrs)

		setErrors(props.formId, [props.errors.message], errorBag)
	}
)

const submitHandler = values => emit('form-submitted', values)
</script>

<style>
.form__submit {
	background-color: inherit !important;
}
</style>
