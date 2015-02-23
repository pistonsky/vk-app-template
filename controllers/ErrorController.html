<?php

namespace app\controllers;

use Yii;

class ErrorController extends \yii\web\Controller
{
    public function actionError()
    {
        $exception = Yii::$app->getErrorHandler()->exception;

        if (($exception) === null) {
            return '';
        }

        $code = $exception->statusCode;
        $name = $exception->getName();

        if (Yii::$app->request->isAjax) {
            header('Content-type: application/json');
            echo json_encode([
                'error' => [
                    'code' => $code,
                    'msg' => $msg
                ]
            ]);
            exit;
        } else {
            return $this->render('error', [
                'name' => $name,
                'code' => $code
            ]);
        }
    }
}
