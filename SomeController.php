<?php

namespace frontend\controllers;

use Yii;
use common\models\Orders;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\Response;

class SomeController extends Controller {

    // ... { Some actions...  }

    public function actionOrder() {
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      /*---------------------------- Check Recaptcha v3---------------------------------*/
  		$secretKey = 'YOUR_SECRET_KEY_GOOGLE_API_RECAPTCHA';
		$result = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['token'].'&remoteip='.$_SERVER['REMOTE_ADDR']);
  		$data = json_decode($result, TRUE);
      /*---------------------------- End Check Recaptcha v3---------------------------------*/

        $order = new \common\models\Orders();
        if ($order->load(Yii::$app->request->post())) {
        	if ($order->validate()) {
	        	if ($data['success']) {
		        	if ($order->save()) {
		        		// Return success operation
        				return ['result'=> true, 'message' => Yii::t('mail', 'Thank you for booking a service on our Website')];
        			}
	        	} else {
	        		// User is Bot !
		    		return ['result'=> false, 'message' => $data];
	        	}
        	} else {
	            $attributes = $order->activeAttributes();
	            // Return validation errors
    	        return ['result' => false, 'errors' => ActiveForm::validate($order, $attributes)];
        	}
        }
    }

    public function actionOrderProcess(){
        $model = new Orders();
        $request = \Yii::$app->request;
        if ($request->isPost && $model->load($request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $attributes = $model->activeAttributes();
            return ActiveForm::validate($model, $attributes);
        }
    }

}
