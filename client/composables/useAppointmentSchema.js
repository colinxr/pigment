import { ref } from 'vue'
import { createInput } from '@formkit/vue'
import { convertToIsoString } from './useDateService'
import CalendarInput from '@/components/Forms/CalendarInput.vue'
import AppointmentDurationInput from '@/components/Forms/AppointmentDurationInput.vue'

import { AutoCompleteInput } from '#components'

const startDateTime = ref('')

const Calender = createInput(CalendarInput)
const DurationInput = createInput(AppointmentDurationInput)
const AutocompleteComponent = createInput(AutoCompleteInput, {
	props: ['list', 'keysToSearch', 'valueToShow'],
})

const appointmentForSubmission = [
	{
		$formkit: 'text',
		label: 'Appointment Name',
		name: 'name',
		validation: 'required',
		value: 'test',
		validationVisibility: 'dirty',
	},
	{
		$formkit: 'textarea',
		label: 'Description',
		name: 'description',
		validation: 'required',
		validationVisibility: 'dirty',
	},
	{
		$formkit: DurationInput,
		label: 'Appointment Duration',
		name: 'duration',
		validation: 'required',
		// help: 'How long is the appointment going to take?',
		validationVisibility: 'dirty',
		// 'outer-class': 'w-1/2',
	},

	{
		$formkit: Calender,
		label: 'Start Time',
		id: 'startDateTime',
		name: 'startDateTime',
		validation: 'required',
		validationVisibility: 'dirty',
		value: '$get(duration).value',
		// 'outer-class': 'w-1/2',
	},
	{
		$el: 'div',
		attrs: {
			class: 'flex gap-4',
		},
		children: [
			{
				$formkit: 'number',
				label: 'Price',
				name: 'price',
				validation: 'required',
				validationVisibility: 'dirty',
				'outer-class': 'w-1/2',
			},
			{
				$formkit: 'number',
				label: 'Deposit',
				name: 'deposit',
				'outer-class': 'w-1/2',
			},
		],
	},
]

const newAppointment = [
	{
		$formkit: AutocompleteComponent,
		label: 'Client',
		name: 'client',
		validation: 'required',
		validationVisibility: 'dirty',
	},
	{
		$formkit: 'text',
		label: 'Appointment Name',
		name: 'name',
		validation: 'required',
		validationVisibility: 'dirty',
	},
	{
		$formkit: 'textarea',
		label: 'Description',
		name: 'description',
		validation: 'required',
		validationVisibility: 'dirty',
	},
	{
		$formkit: DurationInput,
		label: 'Appointment Duration',
		name: 'duration',
		validation: 'required',
		// help: 'How long is the appointment going to take?',
		validationVisibility: 'dirty',
		// 'outer-class': 'w-1/2',
	},

	{
		$formkit: Calender,
		label: 'Start Time',
		name: 'startDateTime',
		id: 'startDateTime',
		validation: 'required',
		validationVisibility: 'dirty',
		// 'outer-class': 'w-1/2',
	},
	{
		$el: 'div',
		attrs: {
			class: 'flex gap-4',
		},
		children: [
			{
				$formkit: 'number',
				label: 'Price',
				name: 'price',
				validation: 'required',
				validationVisibility: 'dirty',
				'outer-class': 'w-1/2',
			},
			{
				$formkit: 'number',
				label: 'Deposit',
				name: 'deposit',
				'outer-class': 'w-1/2',
			},
		],
	},
]

const schemas = {
	newAppointment,
	appointmentForSubmission,
}

export default () => {
	const addPropsToSchema = (name, config = null) => {
		const formSchema = schemas[name]

		if (!formSchema) {
			console.error(
				'make sure this schema exists and was added to the schema object.'
			)
		}

		if (!config) return formSchema

		return handleSchemaConfig(formSchema, config)
	}

	const handleSchemaConfig = (array, config) => {
		return array.map(item => {
			if (item.children) {
				item.children = handleSchemaConfig(item.children, config)

				return item
			}

			const value = Object.keys(config).find(key => key === item.name)

			if (!value) return item

			return { ...item, ...config[value] }
		})
	}

	const setStartDateTime = dateTimeString => {
		console.log(dateTimeString)
		console.log(convertToIsoString(dateTimeString))
		// 2023-06-23T14:08
		// 		const dateParts = dateTimeString.split(" ");
		// const date = dateParts[0];
		// const time = dateParts[1].substring(0, 5);

		// const formattedDateTime = `${date}T${time}`;
		startDateTime.value = convertToIsoString(dateTimeString)

		// startDateTime.value = new Date(dateTimeString)
		// 	.toISOString()
		// 	.replace('.000', '')

		console.log('date time has changes')
		console.log(startDateTime.value)
	}

	return {
		startDateTime,
		appointmentForSubmission,
		newAppointment,
		addPropsToSchema,
		setStartDateTime,
	}
}
