<?php
function ode(){
			$input_xml='<?xml version="1.0" encoding="UTF-8"?>';
			$input_xml .="<TKKPG>";
			$input_xml .="<Request>";
			$input_xml .="<Operation>CreateOrder</Operation>";
			$input_xml .="<Language>AZ</Language>";
			$input_xml .="<Order>";
			$input_xml .="<OrderType>Purchase</OrderType>";
			$input_xml .="<Merchant>E1070034</Merchant>";
			// $input_xml .="<Merchant>E1100005</Merchant>";
			$input_xml .="<Amount>".(($hazirprice) *100)."</Amount>";
			$input_xml .="<Currency>944</Currency>";
			$input_xml .="<Description> Aciqlama </Description>"; //bura istediyiniz achiqlama yaza bilersiz Payment for user id:".$userId."  bunlarin yerine
			$input_xml .="<ApproveURL>https://alindi.az</ApproveURL>";
			$input_xml .="<CancelURL>https://sizistemediz.az/</CancelURL>";
			$input_xml .="<DeclineURL>https://alinmadi.az/</DeclineURL>";
			$input_xml .="</Order>";
			$input_xml .="</Request>";
			$input_xml .="</TKKPG>";


			$url = "https://e-commerce.kapitalbank.az:5443/Exec";
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_SSLCERT, getcwd().'/key.crt');
			curl_setopt($ch, CURLOPT_SSLKEY, getcwd().'/key.key');
			curl_setopt($ch, CURLOPT_KEYPASSWD, '');
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);

			$data = curl_exec($ch);
			$err = curl_errno($ch)." ".curl_error($ch);

			curl_close($ch);

			$array_data = json_decode(json_encode(simplexml_load_string($data)), true);
			$status = $array_data['Response']['Status'];


	if ($status=="00"){

		$orderId = $array_data['Response']['Order']['OrderID'];
		$sessionId = $array_data['Response']['Order']['SessionID'];
		$url = $array_data['Response']['Order']['URL'];




		$_SESSION['orderId'] =$orderId;
		$_SESSION['sessionId']= $sessionId;
		header('Access-Control-Allow-Origin: https://site/');
		header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
		$link = 'https://e-commerce.kapitalbank.az/index.jsp?'."ORDERID=".$orderId."&SESSIONID=".$sessionId;
		$_SESSION['orderId']= $orderId;
		$_SESSION['sessionId']= $sessionId;

		$time = date("Y-m-d H:i:s");
		$myfile = fopen("saleLog.txt", "a");
		fwrite($myfile, $time." : ".$link."\n");
		fclose($myfile);



					ob_start();
			
			header('Location:'.$link);   	
			}
            else if ($status=="30"){
			echo "Məcburi sahələr doldurulmayıb!";
			header('Location: https://site/');
			}
            else{
				echo "Xeta bash verdi!".$status;
			}
			
        }
            
            ?>