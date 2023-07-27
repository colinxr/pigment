<script setup>
import { ref, watch } from 'vue'
import { FormKit, FormKitSchema, setErrors } from '@formkit/vue'

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

watch(
	() => props.errorState,
	newErrorState => {
		const errorBag = buildFormErrorBag(newErrorState.validationErrs)

		setErrors(props.formId, [props.errors.message], errorBag)
	}
)

watch(
	() => props.modelToUpdate,
	({ modelKey, value }) => {
		console.log('got here')
		form.value[modelKey] = value
		console.log(form.value)
	}
)

const submitHandler = values => emit('form-submitted', values)
</script>

<template>
	<FormKit
		:id="formId"
		v-model="form"
		type="form"
		:disabled="disabled"
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
