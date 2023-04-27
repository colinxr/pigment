<?php


return [
  'default_auth_profile' => env('GOOGLE_CALENDAR_AUTH_PROFILE', 'service_account'),
  'api_key' => env('GOOGLE_CLOUD_API_KEY'),

  'auth_profiles' => [

    /*
     * Authenticate using a service account.
     */
    'service_account' => [
      'credentials_json' => storage_path('app/google/ServiceAccountCredentials.json'),
    ],
    /*
     * Authenticate with actual google user account.
     */
    'oauth' => [
      /*
         * Path to the json file containing the oauth2 credentials.
         */
      'credentials_json' => storage_path('app/google/OAuthAccountCredentials.json'),

      /*
         * Path to the json file containing the oauth2 token.
         */
      // 'token_json' => storage_path('app/google-calendar/oauth-token.json'),
    ],
  ]
];
