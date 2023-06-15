<?php
function storeinDb(&$value, $key)
{
	$linkedinPattern = '/linkedin\.com\/in\/([\w-]+)/';
	$emailPattern = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/';

	// Perform the regular expression match for LinkedIn link
	preg_match($linkedinPattern, $value, $linkedinMatches);
	$linkedinLink = isset($linkedinMatches[0]) ? $linkedinMatches[0] : '';

	// Perform the regular expression match for email
	preg_match($emailPattern, $value, $emailMatches);
	$email = isset($emailMatches[0]) ? $emailMatches[0] : '';

	// Extract the string before "Contact Info"
	$contactName = strstr($value, 'Contact Info', true);

	$value = [
		'contactName' => trim($contactName),
		'emailAddress' => $email,
		'linkedInProfileLink' => $linkedinLink

	];
	
}
