<script setup>
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'

import FileInput from '@/components/Forms/FileInput.vue'

const emit = defineEmits(['sendMsg'])

const msgBody = ref('')
const attachments = ref([])
const formElement = ref(null)
const isFocused = ref(false)

const handleSubmit = async () => {
	emit('sendMsg', {
		body: msgBody.value,
		attachments: attachments.value,
	})

	msgBody.value = ''
	files.value = []
	isFocused.value = false
}

const handleNewAttachments = ({ files }) => {
	attachments.value = [...attachments.value, ...files]
}

const clearAttachments = () => {
	attachments.value = []
}

const handleRemove = ({ files }) => {
	attachments.value = files
}

onClickOutside(formElement, () => (isFocused.value = false))
</script>

<template>
	<div class="menu-ba mb-2 bg-color-white">
		<FileInput
			@select="handleNewAttachments"
			@remove="handleRemove"
			@clear="clearAttachments"
		/>
	</div>

	<form @submit.prevent="handleSubmit">
		<div class="flex flex-row items-center" ref="formElement">
			<div
				class="flex flex-row w-full border rounded-3xl p-4 overflow-visible"
				:class="{
					'h-[175px]': isFocused,
					'items-center h-12': !isFocused,
				}"
			>
				<textarea
					v-model="msgBody"
					type="textarea"
					name="message"
					class="border border-transparent w-full bg-white focus:outline-none text-sm flex resize-none"
					:class="{
						'h-full': isFocused,
						'h-[20px]': !isFocused,
					}"
					placeholder="Type your message...."
					@focus="isFocused = !isFocused"
					@keypress.enter="handleSubmit"
				/>

				<!-- <div class="flex flex-row">
            <button class="flex items-center justify-center h-10 w-8 text-gray-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                </path>
              </svg>
            </button>
            <button class="flex items-center justify-center h-10 w-8 text-gray-400 ml-1 mr-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
              </svg>
            </button>
          </div>
        </div> -->

				<div class="ml-4">
					<button
						type="submit"
						:disabled="!msgBody"
						class="flex items-center justify-center h-10 w-10 rounded-full text-white"
						:class="{
							'bg-gray-200': !msgBody,
							'bg-indigo-700 hover:bg-indigo-900': msgBody,
						}"
					>
						<i class="pi pi-arrow-circle-up text-2xl"></i>
					</button>
				</div>
			</div>
		</div>
	</form>
</template>
