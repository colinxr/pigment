<template>
  <Form :initial-values="formValues" @submit="handleSubmit($event, onSubmit)">
    <div
      v-for="{ as, name, label, children, ...attrs } in schema.fields"
      :key="name"
      class="mb-5 flex flex-col"
    >
      <label class="mb-1 block text-base text-[#07074D]" :for="name">{{ label }}</label>

      <Field
        :id="name"
        :as="as"
        :name="name"
        v-bind="attrs"
        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-4 text-base font-medium
        text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
      >
        <template v-if="children && children.length">
          <component
            :is="tag"
            v-for="({ tag, text, ...childAttrs }, idx) in children"
            :key="idx"
            v-bind="childAttrs"
          >
            {{ text }}
          </component>
        </template>
      </Field>
      <ErrorMessage :name="name" />
    </div>

    <div class="flex">
      <button
        class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8
          text-base font-semibold text-white outline-none"
      >
        {{ btnText }}
      </button>

      <span v-if="successMessage">{{ successMessage }}</span>
    </div>
  </Form>
</template>

<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import { ref } from 'vue'

const props = defineProps({
  schema: {
    type: Object,
    required: true
  },
  successMessage: {
    type: String,
    default: ''
  }
})

const formState = ref('')

const btnText = computed(() => {
  if (formState.value === 'pending') { return 'Submitting ...' }

  return 'Submit'
})

const onSubmit = (value) => {
  console.log(JSON.stringify(value))
}
</script>
