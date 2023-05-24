<template>
  <div class="grid h-screen place-items-center">
    <form class="w-1/4" @submit.prevent="handleSubmit">
      <div class="form-control mb-5">
        <TextInput
          id="email"
          v-model="email"
          label-text="email"
        />
      </div>

      <div class="form-control mb-5">
        <TextInput
          id="password"
          v-model="password"
          label-text="password"
          field-type="password"
        />
      </div>

      <button
        class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8
          text-base font-semibold text-white outline-none"
        type="submit"
      >
        Login
      </button>

      <div v-if="errorState.isSet" class="alert alert-error shadow-lg w-full max-w-xs mt-5">
        <div>
          <span>{{ errorState.message }}</span>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ApiService from '@dayplanner/apiservice'

import useAuthStore from '@/stores/auth'

import TextInput from '@/components/Forms/TextInput.vue'

const store = useAuthStore()
const { errorState, handleResponseErrors } = useFormErrors()

definePageMeta({
  middleware: 'user-is-authenticated'
})

const email = ref('')
const password = ref('')

const handleSubmit = async () => {
  try {
    const response = await ApiService.auth.login({
      email: email.value,
      password: password.value
    })

    if (response.status !== 200) {
      return handleResponseErrors(response)
    }

    store.setUser(response.data.user)

    return navigateTo('/')
  } catch (error) {
    errorState.isSet = true
    errorState.message = 'something went wrong'
    return false
  }
}
</script>
