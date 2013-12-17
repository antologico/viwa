<?php

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'tmhOAuthExample.php';
$tmhOAuth = new tmhOAuthExample();

$code = $tmhOAuth->apponly_request(array(
  'method' => 'GET',
  'url' => $tmhOAuth->url('/application/rate_limit_status'),
  'params' => array(
    'q' => 'tmhoauth'
  )
));

$tmhOAuth->render_response();