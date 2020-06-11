<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

<div class="form-body">
    <?php $form = ActiveForm::begin([
        'id' => 'form-order',
        'method' => 'post',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::toRoute('some/order-process'),
    ]) ?>

    <!-- { Some form fields } -->

    <input type="hidden" id="token" name="token" />
	
	<button class="btn btn-primary" onclick="submitForm()">Submit</button>

    <?php ActiveForm::end(); ?>
</div>

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
