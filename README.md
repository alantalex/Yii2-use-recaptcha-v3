# Yii2-use-recaptcha-v3

One example of how to enable user verification in Yii2 framework.

1) In the form in the form, create a hidden token field.

2) When you click on submit, we make a request for a token to the API 
recaptcha. The response is written in the token field. We submit the form already 
with the token to the AJAX server request.

<pre>    
	<script>
	const submitForm = () => {
		grecaptcha.execute('YOUR_SECRET_KEY_GOOGLE_API_RECAPTCHA', {action: 'checkUser'})
		.then(function(token) {
			$('#token').val(token)
		})
		.then(() => {
			$.ajax({
				url: 'some/order',
				method:'post',
				data:$('#form-order').serialize(),
				success: function(data) {
					if (data.result) {
						alert(data.message);
					} else {
						let message = '';
						if (data.errors) {
						console.log(data.errors)
							for (let key in data.errors) {
                				message += data.errors[key] + "\r\n";
								$('.field-' + key).addClass('has-error');
  	  	  	  				}
							alert(message);
						}
					}
				} 
			});
		})
	}
	</script>
</pre>    

3) In the controller, we make a request for user verification.

<pre>    
	$secretKey = 'YOUR_SECRET_KEY_GOOGLE_API_RECAPTCHA';
	$result = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['token'].'&remoteip='.$_SERVER['REMOTE_ADDR']);
	$data = json_decode($result, TRUE);
</pre>    

4) If successful, we accept and save the form data.

<pre>    
	if ($data['success']) {
    	if ($order->save()) {
    		// Return success operation
			return ['result'=> true, 'message' => Yii::t('mail', 'Thank you for booking a service on our Website')];
		}
	} else {
		// User is Bot !
		return ['result'=> false, 'message' => $data];
	}
</pre>    
