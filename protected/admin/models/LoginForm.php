<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
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
            array('username, password', 'required'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
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
            'username' => Yii::t('common', 'username'),
            'password' => Yii::t('common', 'password'),
            'rememberMe' => Yii::t('common', 'rememberMe'),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Incorrect username or password.');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            $userInfo = AdminUserModel::model()->find('username=:username', array(':username' => $this->username));
            Yii::app()->session['userInfo'] = array('uid'=>$userInfo->uid,'username'=>$userInfo->username,'nickname'=>$userInfo->nickname,'group_id'=>$userInfo->group_id);
            //log
            $log = new ActiveRecordLog;
            $log->description =  Yii::t('admin/activeLog', 'User {username} login',array('username'=>Yii::app()->user->Name));
            $log->action = 'LOGIN';
            $log->model = __CLASS__;
            $log->idModel = $userInfo->uid;
            $log->field = '';
            $log->created_at = new CDbExpression('NOW()');
            $log->username = Yii::app()->user->id;
            $log->save();
            return true;
        } else
            return false;
    }
}
