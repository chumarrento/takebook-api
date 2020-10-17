<?php

return [
	'VAPID' => [
		'subject' => 'mailto:felipebarros.dev@gmail.com',
		'publicKey' => env('PUBLIC_KEY'),
		'privateKey' => env('PRIVATE_KEY'),
	],
];
