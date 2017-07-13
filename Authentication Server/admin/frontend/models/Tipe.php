<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tipe".
 *
 * @property integer $id
 * @property string $tipe_app
 *
 * @property Client[] $clients
 */
class Tipe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipe_app'], 'required'],
            [['tipe_app'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipe_app' => 'Tipe App',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['tipe_id' => 'id']);
    }
}
