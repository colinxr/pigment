import { createInput } from '@formkit/vue'
import AppointmentDurationInput from '@/components/Forms/AppointmentDurationInput.vue'

const durationInput = createInput(AppointmentDurationInput)

export default () => {
	const schema = [
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
			$formkit: durationInput,
			label: 'Appointment Duration',
			name: 'duration',
			validation: 'required',
			// help: 'How long is the appointment going to take?',
			validationVisibility: 'dirty',
			// 'outer-class': 'w-1/2',
		},

		{
			$formkit: 'datetime-local',
			label: 'Start Time',
			name: 'startDateTime',
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
	return { schema }
}
