<?php

namespace AnkitJain\RegistrationModule;
require (dirname(__DIR__) . '/registrationModule/vendor/autoload.php');
use AnkitJain\RegistrationModule\Session;

if(Session::get('start') != null)
{
	Session::forget('start');
	header('Location: index.php');
}
else
{
	echo "Please Login";
}
