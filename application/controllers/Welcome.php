<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'vendor/autoload.php';

use ConvertApi\ConvertApi;

class Welcome extends CI_Controller
{
	public function index()
	{
		try {
			ConvertApi::setApiSecret('Tjl6v23hOQC98jPQ');

			$docPath =  FCPATH . "assets/Linkedin_Connections.docx";

			$result = ConvertApi::convert('txt', ['File' => $docPath, 'Options' => [
				'TextEncoding' => 'UTF-8'
			]]);

			# save to file
			$result->getFile()->save(FCPATH . "assets/file.txt");

			$string = $result->getFile()->getContents();

			//replace characters like �  with nothing
			$cleanString = preg_replace('/[^\w\s\/.@]/', '', $string);

			/** 
			 * split the string using a digit with a so that we will get the below in an array with two elements		 * 
			 */

			/*
			1. Myo Han Htay 
			Contact Info 
			Myo Han�s Profile 
			linkedin.com/in/myo-han-htay-9517a898 
			Email 
			dereklaw3@gmail.com 
			Connected 
			November 23, 2021 
			2. Cayla Baldo RN, BSN, PHN 
			Contact Info 
			Cayla�s Profile 
			linkedin.com/in/cayla-baldo-rn-bsn-phn-072627b8 
			Email 
			caylaphoria@gmail.com 
			Connected 
			November 23, 2021 
		 */

			$blocks = preg_split('/\d+\./', $cleanString, -1, PREG_SPLIT_NO_EMPTY);
			$blocks = array_map('trim', $blocks);

			array_walk($blocks, 'storeinDb');
			$this->db->insert_batch('linkedindata', $blocks);

			echo "<h5>Document has been parsed and data has been inserted into the dtabase succesfully</h5>";
		} catch (Exception $e) {
			echo "Failed to parse due to:" . $e->getMessage();
		}
	}
}
