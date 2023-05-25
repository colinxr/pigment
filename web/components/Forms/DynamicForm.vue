<template>
  <FormKit :id="formId" v-model="form" type="form" @submit="submitHandler">
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
  }
})

const form = ref({})

watch(
  () => ({ schema: props.schema, data: props.data }),
  ({ schema, data }) => {
    const newForm = {}
    const object = schema.map(field => field.name)

    object.forEach((field) => {
      newForm[field] = data[field]
    })

    form.value = newForm
  },
  { immediate: true },
)

watch(
  () => props.errorState,
  (newErrorState) => {
    const errorBag = buildFormErrorBag(newErrorState.validationErrs)

    setErrors(props.formId, [props.errors.message], errorBag)
  }
)

const submitHandler = values => emit('form-submitted', values)

</script>
