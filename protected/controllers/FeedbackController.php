<?php

namespace itnew\controllers;

use itnew\models\Feedback;
use itnew\models\Site;
use CController;
use Yii;
use PHPMailer;
use CHttpException;

/**
 * Файл класса FeedbackController.
 *
 * Контроллер для работы с обратной связью
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class FeedbackController extends ContentController
{

	/**
	 * Панель управления
	 * Выводит на экран или получает html-код
	 *
	 * @param bool $return получить ли html-код (в противном случае выводит на экран)
	 *
	 * @return string|void
	 */
	public function actionPanel($return = false)
	{
		return $this->actionContentPanel(
			$return,
			Yii::t("feedback", "Feedback"),
			Yii::t("feedback", "qwe")
		);
	}

	/**
	 * Отправляет сообщение
	 *
	 * @throws CHttpException
	 *
	 * @return void
	 */
	public function actionSend()
	{
		$id = Yii::app()->request->getPost("id");
		if (!$id) {
			throw new CHttpException(400, Yii::t("errors", "Invalid AJAX request"));
		}

		$model = Feedback::model()->findByPk($id);
		if (!$model) {
			throw new CHttpException(400, Yii::t("errors", "Model is not found"));
		}

		$name = Yii::app()->request->getPost("feedback{$id}Name");
		$email = Yii::app()->request->getPost("feedback{$id}Email");
		$phone = Yii::app()->request->getPost("feedback{$id}Phone");
		$adress = Yii::app()->request->getPost("feedback{$id}Adress");
		$subjectUser = Yii::app()->request->getPost("feedback{$id}Subject");
		$message = Yii::app()->request->getPost("feedback{$id}Message");

		$data["status"] = false;

		require_once Yii::getPathOfAlias("application.vendors.PHPMailer.PHPMailer") . "/PHPMailerAutoload.php";

		$mail = new PHPMailer();

		$mail->isSMTP();
		$mail->Host = Yii::app()->params["feedback"]["host"];
		$mail->Port = Yii::app()->params["feedback"]["port"];
		$mail->SMTPAuth = true;
		$mail->Username = Yii::app()->params["feedback"]["user"];
		$mail->Password = Yii::app()->params["feedback"]["password"];
		$mail->SMTPSecure = 'tls';

		$mail->From = Yii::app()->params["feedback"]["user"];
		$mail->FromName = ($name) ? $name : Yii::app()->params["feedback"]["name"];

		if ($email) {
			if ($name) {
				$mail->addReplyTo($email, $name);
			} else {
				$mail->addReplyTo($email);
			}
		}

		$mail->addAddress(($model->email_to) ? $model->email_to : Site::getEmail());

		$blockName = $model->getBlock()->name;
		$modelName = Yii::t("feedback", "Feedback");
		if ($blockName == $modelName) {
			$subject = $modelName;
		} else {
			$subject = $model->getBlock()->name . " | " . $modelName;
		}
		if ($subjectUser && $subjectUser != $blockName && $subjectUser != $modelName) {
			$subject = $subjectUser . " | " . $subject;
		}

		$body = "";
		if ($phone) {
			$body .= "<p><strong>" . Yii::t("feedback", "Phone") . ":</strong> {$phone}</p>";
		}
		if ($adress) {
			$body .= "<p><strong>" . Yii::t("feedback", "Adress") . ":</strong> {$adress}</p>";
		}
		if ($message) {
			$body .= "<p>{$message}</p>";
		}
		if ($email) {
			$body .=
				"<p><strong>" .
				Yii::t("feedback", "E-mail for reply") .
				":</strong> <a href=\"mailto:{$email}\">{$email}</a></p>";
		}

		if (!$body) {
			$body = Yii::t("feedback", "Feedback");
		}

		$mail->CharSet = "UTF-8";
		$mail->Subject = $subject;
		$mail->isHTML(true);
		$mail->Body = $body;

		if ($mail->send()) {
			$data["status"] = true;
		} else {
			$data["status"] = $mail->ErrorInfo;
		}

		echo json_encode($data);
	}
}