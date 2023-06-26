export default () => {
	const schema = [
		{
			$formkit: 'text',
			label: 'First Name',
			name: 'first_name',
			validation: 'required',
			value: 'test',
			validationVisibility: 'blur',
		},
		{
			$formkit: 'text',
			label: 'Last Name',
			name: 'last_name',
			validation: 'required',
			validationVisibility: 'blur',
		},

		{
			$formkit: 'text',
			label: 'Username',
			name: 'username',
			placeholder: 'username',
			help: "It's a good idea to use the same as your IG",
			validation: 'required',
			validationVisibility: 'blur',
		},

		{
			$formkit: 'text',
			label: 'Email',
			name: 'email',
			validation: 'required|email',
			validationVisibility: 'blur',
		},

		{
			$formkit: 'password',
			label: 'Password',
			name: 'password',
			validation: 'required',
			validationVisibility: 'blur',
		},

		{
			$formkit: 'password',
			label: 'Confirm Password',
			name: 'password_confirm',
			validation: 'required|confirm',
			validationVisibility: 'blur',
		},
	]

	return { schema }
}
