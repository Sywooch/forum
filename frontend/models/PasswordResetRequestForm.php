<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use common\models\User;
use frontend\jobs\SendEmailJob;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'required','message'=>'请填写邮箱地址'],
            ['email', 'trim'],
            ['email', 'email','message'=>'邮箱格式错误'],
            ['email', 'string','max'=>35],
            ['email','validateEmail']
        ];
    }

    public function validateEmail($attribute){
        if($this->hasErrors()){return false;}
        $redis=Yii::$app->redis;
        $res=$redis->sismember('register_user',$this->email);
        if(!$res){$this->addError($attribute, '此邮箱还没有被注册！');return false;}
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail(){
        $user=User::findOne(['status'=>User::STATUS_ACTIVE,'email' => $this->email]);
        if (!$user) {return false;}
        if($user->password_reset_token){return false;}

        if(!User::isPasswordResetTokenValid($user->password_reset_token)){
            $user->generatePasswordResetToken();
            if (!$user->save()) {return false;}
        }

        return Yii::$app->queue->push(new SendEmailJob([
            'type'=>'reset',
            'title'=>'请重置您的密码',
            'to'=>$this->email,
            'url'=>Url::toRoute(['pass/reset','token'=>$user->password_reset_token],true),
        ]));
    }
}
