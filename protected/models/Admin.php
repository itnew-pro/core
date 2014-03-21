<?php

/**
 * This is the model class for table "admin".
 *
 * The followings are the available columns in table "admin":
 *
 * @property integer $id
 * @property string  $login
 * @property string  $password
 */
class Admin extends CActiveRecord
{

	/**
	 * @var bool
	 */
	public $remember = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "admin";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array("login, password", "required"),
			array("login, password", "length", "max" => 255),
			array("id, login, password", "safe", "on" => "search"),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"windowTitle" => Yii::t("admin", "Login"),
			"login"       => Yii::t("admin", "User"),
			"password"    => Yii::t("admin", "Password"),
			"remember"    => Yii::t("admin", "Remember me"),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare("id", $this->id);
		$criteria->compare("login", $this->login, true);
		$criteria->compare("password", $this->password, true);

		return new CActiveDataProvider($this, array(
			"criteria" => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 *
	 * @return Admin the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Login
	 *
	 * @return string error class
	 */
	public static function login()
	{
		$admin = Yii::app()->request->getPost("Admin");
		$identity = new UserIdentity($admin["login"], $admin["password"]);
		if($identity->authenticate()) {
			$remember = false;
			if ($admin["remember"]) {
				$remember = 60 * 60 * 24 * 30;
			}
			Yii::app()->user->login($identity, $remember);
		}
		else {
			return $identity->errorClass;
		}

		return;
	}
}