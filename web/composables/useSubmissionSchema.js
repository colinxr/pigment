export default () => {
	const schema = [
		{
			$formkit: 'text',
			label: 'First Name',
			name: 'first_name',
			validation: 'required',
			value: 'test',
			validationVisibility: 'dirty',
		},
		{
			$formkit: 'text',
			label: 'Last Name',
			name: 'last_name',
			validation: 'required',
			validationVisibility: 'dirty',
		},

		{
			$formkit: 'text',
			label: 'Email',
			name: 'email',
			validation: 'required',
			validationVisibility: 'dirty',
		},

		{
			$formkit: 'tel',
			label: 'Phone Number',
			name: 'phone',
			validationVisibility: 'dirty',
		},

		{
			$formkit: 'textarea',
			label: 'Message',
			name: 'idea',
			validation: 'required',
			validationVisibility: 'dirty',
		},
	]

	return { schema }
}
