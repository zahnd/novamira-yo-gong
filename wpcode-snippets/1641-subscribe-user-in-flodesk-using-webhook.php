<?php
add_action('fluentform/before_insert_submission', function($insertData, $data, $form) {
	if($form['id'] == 4) {
		// is newsletter form
		$first_name = $data['names']['first_name'];
		$email = $data['email'];
		
		$url = "https://server.bergwerk.li:9000/hooks/yogong-flodesk";
		$headers = [ 'Content-Type: application/json' ];
		$msg = [ 'first_name' => $first_name, 'email' => $email ];

		$webhook_url = 'https://server.bergwerk.li:9000/hooks/yogong-flodesk';
		//$msg = ["content" => "Hi, this is the message sent from php"];
		$headers = array('Content-Type: application/json'); 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, $webhook_url );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $msg ) );
		$response = curl_exec( $ch );
		curl_close( $ch );
	}
}, 10, 3);
