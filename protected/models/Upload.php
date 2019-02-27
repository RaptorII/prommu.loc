<?php

class Upload
{
	/**
	 * 
	 */
	public static function setCanvas($path, $name, $file)
	{
		$fullPath = Subdomain::domainRoot() . $path . $name . '.jpg';

		$strData = str_replace('data:image/png;base64,', '', $file);
		$strData = str_replace(' ', '+', $strData);
		$data = base64_decode($strData);

		$result = file_put_contents($fullPath, $data);

		return ['error' => $result];
	}
}