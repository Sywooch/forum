<?php
namespace frontend\models;

use yii\base\Model;
use yii\web\BadRequestHttpException;
use common\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $repassword;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if(empty($token)){throw new BadRequestHttpException('请填写重置码');}

        if(!is_string($token)||strpos($token,"'")!==false||strpos($token,'%')!==false){
            throw new BadRequestHttpException('重置码格式错误');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new BadRequestHttpException('重置码错误');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required','message'=>'请填写密码'],
            ['password','trim'],
            ['repassword','required','message'=>'请填写确定密码'],
            ['repassword','trim'],
            ['password', 'string', 'length' => [6, 12],'message'=>'密码长度须为6-12位'],
            ['password','match','pattern' =>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]+$/','message'=>'密码须为字母和数字组合'],
            ['repassword', 'string', 'length' => [6, 12],'message'=>'确认密码长度须为6-12位'],
            ['repassword','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]+$/','message'=>'确认密码须为字母和数字组合'],
            ['password','compare','compareAttribute'=>'repassword','message'=>'密码和确认密码不一致'],
        ];
    }

    public function attributeLabels(){
        return [
            'password' => '密码',
            'repassword' => '充值密码',
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
}
