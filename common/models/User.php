<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use frontend\models\Post;
use frontend\models\Sign;
use frontend\models\Moder;
use frontend\models\Comment;
use frontend\models\Draft;
use frontend\models\Site;
use frontend\models\Prive;
use frontend\models\Collection;
use frontend\models\Level;
use frontend\models\Star;
use frontend\models\Report;
use frontend\models\Reset;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface{
    const STATUS_BEAVTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_TIMEOUT = 3;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_BEAVTIVE],
            ['status', 'in', 'range' => [self::STATUS_BEAVTIVE, self::STATUS_ACTIVE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id){
        return static::find()->select('id,email,password,username,avatar,city,sex,level,experience,integral,intro,groups,status,ip,created_at,update_at,view')->where(['id'=>$id,'status'=> self::STATUS_ACTIVE])->cache(15)->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email){
        return static::find()->select('id,email,password,username,avatar,city,sex,level,experience,integral,intro,groups,status,ip,created_at,update_at,view')->where(['email'=>$email])->one();
    }

    public static function findByEmails($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE,'groups'=>1]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)){return null;}

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {return false;}
        $info=Reset::find()->where(['token'=>$token])->one();
        if(empty($info)||empty($info['create_d'])){return false;}
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $info['create_d'] + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $resetToken=Yii::$app->security->generateRandomString();
        $resetModel=new Reset();
        $resetModel->token=$resetToken;
        $resetModel->create_d=time();
        $resetModel->save();
        $this->password_reset_token =$resetToken;
        $this->update_at=time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 用户和帖子一对多
     */
    public function getPosts(){
        return $this->hasMany(Post::className(),['user_id'=>'id']);
    }

    /**
     * 用户和签到一对多
     */

    public function getSign(){
        return $this->hasMany(Sign::className(),['user_id'=>'id']);
    }

    /**
     * 用户和版主一对一
     */

    public function getModer(){
        return $this->hasOne(Moder::className(),['user_id'=>'id']);
    }
    /**
     * 用户和评论一对多
     */
    public function getComments(){
        return $this->hasMany(Comment::className(),['user_id'=>'id']);
    }

    /**
     * 用户和操作一对多
     */
    public function getDraft(){
        return $this->hasMany(Draft::className(),['user_id'=>'id']);
    }

    /**
     * 用户和站内信一对多
     */

    public function getSites(){
        return $this->hasMany(Site::className(),['user_id'=>'id']);
    }

    /**
     * 用户和私信一对多
     */
    public function getPrives(){
        return $this->hasMany(Prive::className(),['users_id'=>'id'])->select('id,username,email');
    }
    /**
     * 用户和私信一对多
     */

    public function getPrivess(){
        return $this->hasMany(Prive::className(),['user_id'=>'id']);
    }
    /**
     * 用户和收藏一对多
     */
    public function getCollection(){
        return $this->hasMany(Collection::className(),['user_id'=>'id']);
    }
    /**
     * 用户和点赞一对多
     */

    public function getStars(){
        return $this->hasMany(Star::className(),['user_id'=>'id']);
    }

    /**
     * 用户和举报一对多
     */
    public function getReports(){
        return $this->hasMany(Report::className(),['user_id'=>'id']);
    }

    /**
     * 用户身份
     */

    public function role($groups){
        switch($groups){
            case '1':
                return '版主';
                break;
            case '2':
                return '管理员';
                break;
            case '0':
                return '用户';
                break;
        }
    }

    /**
     * @param $uid
     * @param $val
     * @return bool
     */

    public function addExperience($uid,$val){
        $users=static::findOne(['id'=>$uid]);
        $level=$users->level;
        $experience=$users->experience;
        $experience=$experience+$val;
        $levels=Level::find()->select('experi')->where(['level'=>$level+1])->scalar();
        if($experience>=$levels){
            $users->level=$level+1;
            $users->experience=$experience;
            return $users->save()?true:false;
        }
        $users->experience=$experience;
        return $users->save()?true:false;
    }

    public function getUsernameFields($bz_id){
        $userNameList=static::find()->select('username')->filterWhere(['id'=>$bz_id])->asArray()->cache(10)->column();
        return implode(',',$userNameList);
    }

}
