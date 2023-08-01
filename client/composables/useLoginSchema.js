export default () => {
	const schema = [
		{
			$formkit: 'text',
			label: 'Email',
			name: 'email',
			// validation: 'required|email',
			// validationVisibility: 'blur',
		},
		{
			$formkit: 'password',
			label: 'Password',
			name: 'password',
			// validation: 'required',
			// validationVisibility: 'dirty',
		},
	]

	return { schema }
}
