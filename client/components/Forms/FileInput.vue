<script setup>
import FileUpload from 'primevue/fileupload'

const emit = defineEmits(['newAttachments'])

const attachments = ref([])
</script>

<template>
	<FileUpload
		v-model="attachments"
		name="attachments[]"
		:multiple="true"
		accept="image/*"
		:maxFileSize="5000000"
		@select="onSelectedFiles"
	>
		<template #content="{ files }">
			<div v-if="files.length > 0">
				<div class="flex flex-wrap gap-5 mb-5">
					<div
						v-for="(file, index) of files"
						:key="file.name + file.type + file.size"
						class="m-0 flex flex-column border-1 surface-border align-items-center gap-3"
					>
						<div>
							<img
								role="presentation"
								:alt="file.name"
								:src="file.objectURL"
								class="shadow-2 max-h-[100px]"
							/>
						</div>
					</div>
				</div>
			</div>
		</template>

		<template #header="{ chooseCallback, clearCallback, files }">
			<div
				class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2"
			>
				<div class="flex w-full justify-between gap-2">
					<Button
						@click="chooseCallback()"
						icon="pi pi-images"
						rounded
						outlined
					/>
					<Button
						@click="clearCallback()"
						v-if="files && files.length > 0"
						icon="pi pi-times"
						rounded
						outlined
						severity="danger"
						class="h-15 w-15"
					/>
				</div>
			</div>
		</template>
	</FileUpload>
</template>

<style scoped>
.p-fileupload {
	display: flex;
	flex-direction: column-reverse;
}
.p-fileupload .p-fileupload-buttonbar {
	/* background: #fafafa; */
	/* padding: 0.75rem; */
	/* border: 1px solid #e5e7eb; */
	color: #3f3f46;
	border-top-right-radius: 0.375rem;
	border-top-left-radius: 0.375rem;
}
.p-fileupload .p-fileupload-buttonbar .p-button
/* .p-fileupload .p-fileupload-buttonbar .p-button,  */ {
	margin-right: 0.5rem;
	width: 20px;
	height: 20px;
	padding: 1rem;
}
.p-fileupload .p-fileupload-buttonbar .p-button.p-fileupload-choose.p-focus {
	outline: 0 none;
	outline-offset: 0;
	box-shadow: 0 0 0 1px #6366f1;
}
.p-fileupload .p-fileupload-content {
	background: #ffffff;
	color: #3f3f46;
	border-bottom-right-radius: 0.375rem;
	border-bottom-left-radius: 0.375rem;
}
.p-fileupload .p-progressbar {
	height: 0.25rem;
}
.p-fileupload .p-fileupload-row > div {
	padding: 1rem 1.5rem;
}
.p-fileupload.p-fileupload-advanced .p-message {
	margin-top: 0;
}

.p-fileupload-choose:not(.p-disabled):hover {
	background: #4338ca;
	color: #ffffff;
	border-color: #4338ca;
}
.p-fileupload-choose:not(.p-disabled):active {
	background: #4338ca;
	color: #ffffff;
	border-color: #4338ca;
}
</style>
