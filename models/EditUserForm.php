<?php
     
     namespace app\models;
      
     use Yii;
     use yii\base\Model;
     use app\models\User;
      
     class EditUserForm extends Model
     {
      
         public $username;
         public $password;
         public $usernameUpdate = false;
         public $passwordUpdate = false;
      
         /**
          * @inheritdoc
          */
         public function rules()
         {
            return [
                ['username', 'trim'],
                ['username', 'required'],
                ['username', 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.', 'filter' => ['!=', 'username', $this->username]],
                ['username', 'string', 'min' => 2, 'max' => 255],
                ['password', 'string', 'skipOnEmpty' => true],
                ['password', 'string', 'min' => 3],
            ];
         }
      
        /**
         * Edit user.
        * @param int id User id.
        *
        * @return User|null the saved model or null if saving fails
        */
        public function editUser($id)
        {
            if (!$this->validate()) {
                return false;
            }
            $user = User::findIdentity($id);
            if($user->username != $this->username){
                $user->username = $this->username;
                $this->usernameUpdate = true;
            }
            if(!empty($this->password)) {
                $user->setPassword($this->password);
                $this->passwordUpdate = true;
            }
            $result = $user->save();
            return $result;
        }
      
     }