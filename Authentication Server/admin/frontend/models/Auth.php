<?php

namespace app\models;

use Yii;
use app\models\Auth;

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
 */
class Auth extends \yii\db\ActiveRecord
{
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
            [['client_secret', 'access_token', 'url_token', 'unm_token'], 'string', 'max' => 255],
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
            'seq_num' => 'Seq Num',
            'is_active' => 'Is Active',
        ];
    }
}
