export default () => {
	const schema = [
		{
			$el: 'div',
			attrs: {
				class: 'flex gap-4',
			},
			children: [
				{
					$formkit: 'text',
					label: 'First Name',
					name: 'first_name',
					validation: 'required',
					value: 'test',
					validationVisibility: 'dirty',
					outerClass: 'w-1/2',
				},
				{
					$formkit: 'text',
					label: 'Last Name',
					name: 'last_name',
					validation: 'required',
					validationVisibility: 'dirty',
					outerClass: 'w-1/2',
				},
			],
		},

		{
			$el: 'div',
			attrs: {
				class: 'flex gap-4',
			},
			children: [
				{
					$formkit: 'text',
					label: 'Email',
					name: 'email',
					validation: 'required',
					validationVisibility: 'dirty',
					outerClass: 'w-1/2',
				},

				{
					$formkit: 'tel',
					label: 'Phone Number',
					name: 'phone',
					validation: 'matches:/^[0-9]*$/',
					validationMessages: {
						matches: 'Must only have numbers. ',
					},
					validationVisibility: 'dirty',
					outerClass: 'w-1/2',
				},
			],
		},

		{
			$formkit: 'textarea',
			label: 'Message',
			name: 'idea',
			rows: '5',
			validation: 'required',
			validationVisibility: 'dirty',
		},
	]

	return { schema }
}
