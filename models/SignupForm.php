<?php
     
     namespace app\models;
      
     use Yii;
     use yii\base\Model;
      
     class SignupForm extends Model
     {
      
         public $username;
         public $password;
      
         /**
          * @inheritdoc
          */
         public function rules()
         {
             return [
                 ['username', 'trim'],
                 ['username', 'required'],
                 ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
                 ['username', 'string', 'min' => 2, 'max' => 255],
                 ['password', 'required'],
                 ['password', 'string', 'min' => 3],
             ];
         }
      
         /**
          * Signs up user.
          *
          * @return User|null the saved model or null if saving fails
          */
         public function signup()
         {
             if (!$this->validate()) {
                 return null;
             }
             $user = new User();
             $user->username = $this->username;
             $user->setPassword($this->password);
             $user->generateAuthKey();
             $result = $user;
             if(!$user->save()){
                 $result = null;
             }
             return $result;
         }
      
     }