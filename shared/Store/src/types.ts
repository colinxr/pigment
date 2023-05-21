interface GoogleAccessToken {
  access_token: string,
  expires_in: number,
  created: Date,
  refresh_token: string,
}

export type AuthUserI = {
  id: number,
  first_name: string, 
  last_name: string,
  email: string, 
  username: string,
  email_verified_at: boolean|null,
  created_at: Date,
  updated_at:Date,
  access_token: GoogleAccessToken, 
  calendar_id: string,
}

export type SubmissionI = {
  id: number,
  client_id: number,
  user_id: number,
  idea: string, 
  created_at: Date,
  updated_at: Date,
}