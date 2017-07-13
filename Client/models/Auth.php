<?php

namespace app\models;

use Yii;
use app\models\Auth;
use yii\web\UploadedFile;

/**
 * This is the model class for table "auth".
 *
 * @property integer $client_id
 * @property string $client_secret
 * @property string $access_token
 * @property string $url_token
 * @property string $unm_token
 * @property integer $seq_num
 * @property integer $is_active
 * @property string $root_file
 * @property string $init_key
 * @property string $sync_key
 */
class Auth extends \yii\db\ActiveRecord
{
	public $rootFile;
	private $_useLibreSSL;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'client_secret'], 'required'],
            [['client_id', 'seq_num', 'is_active'], 'integer'],
            [['seed','client_secret', 'access_token', 'url_token', 'unm_token', 'root_file', 'init_key', 'sync_key'], 'string', 'max' => 255],
			
			[['rootFile'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'access_token' => 'Access Token',
            'url_token' => 'Url Token',
            'unm_token' => 'Unm Token',
            'seq_num' => 'Sequence Number',
            'is_active' => 'Is Active',
            'root_file' => 'Root File',
            'init_key' => 'Initialization Key',
            'sync_key' => 'Syncronization Key',
        ];
    }
	
	public static function generateToken($seed, $seqnum){
		$seed = crc32($seed.$seqnum);
		mt_srand($seed);
		$token = mt_rand();
		
		return $token;
	}
	
	public static function generateSeed($root_file){
		$file = Yii::getAlias('@webroot').'/assets/'.$root_file;
		$fp = fopen($file, 'r');
		$fsize = filesize($file); 
		$seed = fread($fp, $fsize); 
		$urlseed = substr($seed, $fsize/2, 12);
		$unmseed = substr($seed, -12);
		fclose($fp);
		
		$seed = array(
			'urlseed'=>$urlseed,
			'unmseed'=>$unmseed,
		);
		
		return $seed;
	}
	
	public function generateTokenHash($rootfile, $n1,$n2,$n3,$n4)
	{
		$seed = $this->generateSeed($rootfile);
		$url_seed = $seed['urlseed'];
		$unm_seed = $seed['unmseed'];
		
		$urltoken1 = $this->generateToken($url_seed,$n1);
		$urltoken2 = $this->generateToken($url_seed,$n2);

		$unmtoken1 = $this->generateToken($unm_seed,$n3);
		$unmtoken2 = $this->generateToken($unm_seed,$n4);
		
		return md5($urltoken1.$urltoken2.$unmtoken1.$unmtoken2);
	}
	
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {	
			$this->rootFile = UploadedFile::getInstance($this, 'rootFile');
			
			if(isset($this->rootFile->baseName)){
				if (!$this->isNewRecord) {
					$path = 'assets/'.$this->root_file;
			
					if(file_exists($path))
						unlink($path);
				}
				
				$nama_file = $this->checkFile('assets/',$this->rootFile->baseName,$this->rootFile->extension,'');
				
				$this->rootFile->saveAs('assets/'.$nama_file);
				$this->root_file=$nama_file;
			}
			
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
