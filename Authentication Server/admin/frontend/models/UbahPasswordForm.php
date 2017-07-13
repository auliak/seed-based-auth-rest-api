<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class UbahPasswordForm extends Model
{
    public $password;
    public $new_password;
    public $new_password_repeat;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password','new_password', 'new_password_repeat'], 'required'],
            ['password', 'validatePassword'],
            ['new_password', 'string', 'min' => 6],
			['new_password_repeat', 'compare', 'compareAttribute'=>'new_password', 'message'=>'Password baru harus diulang dengan tepat'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Password yang anda masukkan salah');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(Yii::$app->user->identity->id);
        }

        return $this->_user;
    }
	
	public function ubahpassword()
    {
		if ($this->validate()) {
			$user = User::findOne(Yii::$app->user->identity->id);
			
			$user->setPassword($this->new_password);
			$user->generateAuthKey();
			
			if($user->save())
				return true;
		}
		else
			return false;
    }
}
