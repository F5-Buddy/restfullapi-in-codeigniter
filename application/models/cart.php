<?php
class Cart extends CI_Model{

	private function dotrasaction($trans_id, $amt){
		  $api_version = '86.0';		  
		  $api_endpoint ='https://api-3t.paypal.com/nvp';
		  $api_username='smita_28_api1.yahoo.com';
		  $api_password='RUPG3XMLURQ72HGL';
		  $api_signature='AQ7YrPojVzUY9cDwHYBx9qkayZxDAdRA0oPPkpJwHRvc.r8ovfSE5JFM';
	  	  $request_params = array
					(
					'METHOD' => 'DoReferenceTransaction', 
					'USER' => $api_username, 
					'PWD' => $api_password, 
					'SIGNATURE' => $api_signature, 
					'VERSION' => $api_version, 
					'PAYMENTACTION' => 'Sale', 		
					'AMT' => $amt, 
					'CURRENCYCODE' => 'USD', 
					'PAYMENTACTION' => 'Sale',
					'REFERENCEID' => $trans_id
					);
			//print_r($request_params); //die();
			// Loop through $request_params array to generate the NVP string.
			$nvp_string = '';
			foreach($request_params as $var=>$val){
				$nvp_string .= '&'.$var.'='.urlencode($val);	
			}			
			// Send NVP string to PayPal and store response
			$curl = curl_init();
					curl_setopt($curl, CURLOPT_VERBOSE, 1);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

					curl_setopt($curl, CURLOPT_TIMEOUT, 30);
					curl_setopt($curl, CURLOPT_URL, $api_endpoint);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
			
			$result = curl_exec($curl);
			curl_close($curl);
			// Function to convert NTP string to an array
			function NVPToArray($NVPString){
				$proArray = array();
				while(strlen($NVPString)){
					// name
					$keypos= strpos($NVPString,'=');
					$keyval = substr($NVPString,0,$keypos);
					// value
					$valuepos = strpos($NVPString,'&') ? strpos($NVPString,'&'): strlen($NVPString);
					$valval = substr($NVPString,$keypos+1,$valuepos-$keypos-1);
					// decoding the respose
					$proArray[$keyval] = urldecode($valval);
					$NVPString = substr($NVPString,$valuepos+1,strlen($NVPString));
				}
				return $proArray;
			}			
			// Parse the API response
			$result_array = NVPToArray($result);
			return strtoupper($result_array['ACK']);
	}
	
	public function payment_process($user_email,$total,$card_number,$cvv,$m_year,$cardtype){
		//$user_email = "jitupatel7687@gmail.com";
		//$total = 10;
		//echo $card_number = "4032033764547522";
		//echo $cvv."<br>";
		//echo $m_year;exit;
		 
		  $api_version = '85.0';		  
		  $api_endpoint ='https://api-3t.sandbox.paypal.com/nvp';
		  $api_username='seller_api1.f5buddy.com';
		  $api_password='RBJFE4FEC8C24NN7';
		  $api_signature='AFcWxV21C7fd0v3bYYYRCpSSRl31AVqx94lvzEJJYGUn--jNOsScapd8';
	  	  $request_params = array
					(
					'METHOD' => 'DoDirectPayment', 
					'USER' => $api_username, 
					'PWD' => $api_password, 
					'SIGNATURE' => $api_signature, 
					'VERSION' => $api_version, 
					'PAYMENTACTION' => 'Sale', 					
					'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
					'CREDITCARDTYPE' => $cardtype, 
					'ACCT' => $card_number, 						
					'EXPDATE' => $m_year, 			
					//'CVV2' => $cvv,
					'AMT' => $total, 
					'CURRENCYCODE' => 'USD', 
					'DESC' => 'Garment Rental',
					'EMAIL' => $user_email
					//'DESC' => $short_body
					);
			//print_r($request_params); //die();
			// Loop through $request_params array to generate the NVP string.
			$nvp_string = '';
			
			foreach($request_params as $var=>$val){
				$nvp_string .= '&'.$var.'='.urlencode($val);
			}			
			// Send NVP string to PayPal and store response
			$curl = curl_init();
					curl_setopt($curl, CURLOPT_VERBOSE, 1);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

					curl_setopt($curl, CURLOPT_TIMEOUT, 30);
					curl_setopt($curl, CURLOPT_URL, $api_endpoint);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
			
			$result = curl_exec($curl);
			curl_close($curl);
			// Function to convert NTP string to an array
						
			// Parse the API response
			
			$result_array = $this->NVPToArray($result);
			return $result_array;
						
		}

		function NVPToArray($NVPString){
				$proArray = array();
				while(strlen($NVPString)){
					// name
					$keypos= strpos($NVPString,'=');
					$keyval = substr($NVPString,0,$keypos);
					// value
					$valuepos = strpos($NVPString,'&') ? strpos($NVPString,'&'): strlen($NVPString);
					$valval = substr($NVPString,$keypos+1,$valuepos-$keypos-1);
					// decoding the respose
					$proArray[$keyval] = urldecode($valval);
					$NVPString = substr($NVPString,$valuepos+1,strlen($NVPString));
				}
				return $proArray;
		}	
	
}