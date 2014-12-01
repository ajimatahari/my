<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $hape;
        public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('hape, password', 'required'),
			// rememberMe needs to be a boolean
			//array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
                        'hape'=>'Nomor Handphone',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->hape,$this->password);
                        $varcek=$this->_identity;
                        switch($varcek->errorCode)
                        {
                            case $varcek::ERROR_USERNAME_INVALID:
                                $this->addError('email','Email address is incorrect.');
                                break;
                            default: // UserIdentity::ERROR_PASSWORD_INVALID
                                $this->addError('password','Password is incorrect.');
                                break;
                        }
			/*if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
                            switch($identity->errorCode)
                            {
                                case UserIdentity::ERROR_NONE:
                                    Yii::app()->user->login($identity);
                                    break;
                                case UserIdentity::ERROR_USERNAME_INVALID:
                                    $this->addError('hape','Phone Number is incorrect.');
                                    break;
                                default: // UserIdentity::ERROR_PASSWORD_INVALID
                                    $this->addError('password','Password is incorrect.');
                                    break;
                            }*/
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->hape,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}