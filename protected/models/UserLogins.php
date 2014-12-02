<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class UserLogins extends CFormModel{
        
        public $username;
        public $password;
        
        private $identitas;
        
        public function rules(){
            array('hape,password','required');
            array('password','authenticate'); //=> validator namenya : authenticate
        }
        
        public function authenticate($attribute,$params){
            //validator untuk password
                if(!$this->hasErrors())
		{
			$this->identitas = new UserIdentity($this->user,$this->password);
			if(!$this->identitas->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
                
            /*$userList = UserLogins::model()->findByAttributes(array('username' => $this->hape));
            if($userList===null)
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            else if ($userList->password===$this->password)
            {
                $this->errorCode=self::ERROR_NONE;
            }
            else
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            return !$this->errorCode;
            */
        }
        
        public function login()
	{
		if($this->identitas===null)
		{
			$this->identitas=new UserIdentity($this->user,$this->passwords);
			$this->identitas->authenticate();
		}
		if($this->identitas->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->identitas,$duration);
			return true;
		}
		else
			return false;
	}
        
}