<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $nama_app
 * @property integer $tipe_id
 * @property integer $user_id
 * @property integer $status_id
 * @property string $root_file
 * @property string $url_seed
 * @property string $unm_seed
 * @property integer $seq_num
 * @property string $url_token
 * @property string $unm_token
 * @property string $access_token
 * @property integer $access_token_t
 * @property integer $access_token_to
 * @property string $init_key
 * @property integer $init_key_t
 * @property integer $init_key_to
 * @property string $sync_key
 * @property integer $sync_key_t
 * @property integer $sync_key_to
 * @property string $token_hash
 * @property integer $token_hash_t
 * @property integer $token_hash_to
 * @property string $sync_token_hash
 * @property integer $sync_token_hash_t
 * @property integer $sync_token_hash_to
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Tipe $tipe
 * @property Status $status
 */
class Client extends \yii\db\ActiveRecord
{
	public $rootFile;
	public $rootFile_rule;
	public $tipe_app;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['nama_app', 'tipe_id', 'user_id', 'status_id', 'root_file'], 'required'],
			[['nama_app', 'tipe_id'], 'required'],
            [['tipe_id', 'user_id', 'status_id', 'seq_num', 'access_token_t', 'access_token_to', 'init_key_t', 'init_key_to', 'sync_key_t', 'sync_key_to', 'token_hash_t', 'token_hash_to', 'sync_token_hash_t', 'sync_token_hash_to', 'created_at', 'updated_at'], 'integer'],
            [['nama_app', 'root_file', 'url_seed', 'unm_seed', 'url_token', 'unm_token', 'access_token', 'init_key', 'sync_key', 'token_hash', 'sync_token_hash'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['tipe_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tipe::className(), 'targetAttribute' => ['tipe_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
			
			[['rootFile'], 'file', 'skipOnEmpty' => true, 'minSize' => '25'],
			[['rootFile_rule'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_app' => 'Nama App',
            'tipe_id' => 'Tipe ID',
            'user_id' => 'User ID',
            'status_id' => 'Status ID',
            'root_file' => 'Root File',
            'url_seed' => 'Url Seed',
            'unm_seed' => 'Unm Seed',
            'seq_num' => 'Seq Num',
            'url_token' => 'Url Token',
            'unm_token' => 'Unm Token',
            'access_token' => 'Access Token',
            'access_token_t' => 'Access Token T',
            'access_token_to' => 'Access Token To',
            'init_key' => 'Init Key',
            'init_key_t' => 'Init Key T',
            'init_key_to' => 'Init Key To',
            'sync_key' => 'Sync Key',
            'sync_key_t' => 'Sync Key T',
            'sync_key_to' => 'Sync Key To',
            'token_hash' => 'Token Hash',
            'token_hash_t' => 'Token Hash T',
            'token_hash_to' => 'Token Hash To',
            'sync_token_hash' => 'Sync Token Hash',
            'sync_token_hash_t' => 'Sync Token Hash T',
            'sync_token_hash_to' => 'Sync Token Hash To',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipe()
    {
        return $this->hasOne(Tipe::className(), ['id' => 'tipe_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }
	
	public function getTipeoptions()
	{
		$tipe = Tipe::find()->orderby('id asc')->all();
		$tipe_options = ArrayHelper::map($tipe,'id','tipe_app');
		
		return $tipe_options;
	}
	
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			$this->user_id=Yii::$app->user->identity->id;
		
			$this->rootFile = UploadedFile::getInstance($this, 'rootFile');
			
			if(isset($this->rootFile->baseName)){
				if (!$this->isNewRecord) {
					$path = '../../authenticater/web/file/temp/'.$this->root_file;
			
					if(file_exists($path))
						unlink($path);
				}
				
				$nama_file = $this->checkFile('../../authenticater/web/file/temp/',$this->rootFile->baseName,$this->rootFile->extension,'');
				
				$this->rootFile->saveAs('../../authenticater/web/file/temp/'.$nama_file);
				$this->root_file=$nama_file;
				
				$this->init_key = Yii::$app->security->generateRandomString();
				$this->init_key_t = time();
				$this->status_id = 0;
				$this->init_key_to = 1000;
				$this->seq_num = 0;
			}
			if ($this->isNewRecord) 
				$this->created_at = time();
			else
				$this->updated_at = time();
			
			return true;
		} else {
			return false;
		}
	}
	
	public function checkFile($path, $file, $ext, $number)
	{
		if(file_exists($path.$file.$number.'.'.$ext))
		{
			if($number == "")
				$number = 0;
			$number ++;
			return $this->checkFile($path, $file, $ext, $number);
		}
		
		$nama_file = $file.$number.'.'.$ext;
		
		return $nama_file;
	}
}
