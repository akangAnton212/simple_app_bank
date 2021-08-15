<?php
// return [
//     /*
//     |--------------------------------------------------------------------------
//     | Laravel CORS
//     |--------------------------------------------------------------------------
//     |
//     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
//     | to accept any value.
//     |
//     */
//    'supportsCredentials' => false,
//    'allowedOrigins' => ['*'],
//    'allowedHeaders' => ['Content-Type', 'X-Requested-With', 'Headers'],
//    'allowedMethods' => ['*'], // ex: ['GET', 'POST', 'PUT',  'DELETE']
//    'exposedHeaders' => [],
//    'maxAge' => 0,
// ];
return [
    
    'allow_origins' => ['*'],
    
    'allow_headers' => ['*'],
    
    'allow_methods' => ['*'],
    
    'allow_credentials' => true,
    
    'expose_headers' => [],
    
    'max_age' => 0,
  
    'origin_not_allowed' => null,
    
    'method_not_allowed' => null,
    
    'header_not_allowed' => null,
];