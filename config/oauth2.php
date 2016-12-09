<?php
    //Modify the $grant_types as follow.
return[
	    'grant_types' => [
            'password' => [
             'class' => 'League\OAuth2\Server\Grant\PasswordGrant',
             'access_token_ttl' => 604800,

             // the code to run in order to verify the user's identity
             'callback' => 'App\Http\Controllers\PasswordGrantVerifier@verify',
             ],
        ],
];