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
	attachments.value = []
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

				<div class="ml-4">
					<button
						type="submit"
						:disabled="!msgBody || !attachments"
						class="flex items-center justify-center h-10 w-10 rounded-full text-white"
						:class="{
							'bg-gray-200': !msgBody || !attachments,
							'bg-indigo-700 hover:bg-indigo-900':
								msgBody || attachments.length,
						}"
					>
						<i class="pi pi-arrow-circle-up text-2xl"></i>
					</button>
				</div>
			</div>
		</div>
	</form>
</template>
