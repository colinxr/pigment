<template>
  <FormKit v-model="form" type="form" @submit="submitHandler">
    <FormKitSchema :schema="schema" :data="form" />
  </FormKit>
</template>

<script setup>
/* eslint-disable-next-line */
import { FormKitSchema } from '@formkit/vue'
import { ref } from 'vue'

const emit = defineEmits(['form-submitted'])

const props = defineProps({
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

const submitHandler = values => emit('form-submitted', values)

</script>
