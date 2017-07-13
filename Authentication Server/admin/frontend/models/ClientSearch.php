<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Client;

/**
 * ClientSearch represents the model behind the search form about `frontend\models\Client`.
 */
class ClientSearch extends Client
{
	public $tipe;
	public $status;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tipe_id', 'user_id', 'status_id', 'seq_num', 'access_token_t', 'access_token_to', 'init_key_t', 'init_key_to', 'sync_key_t', 'sync_key_to', 'token_hash_t', 'token_hash_to', 'sync_token_hash_t', 'sync_token_hash_to'], 'integer'],
            [['status', 'tipe', 'nama_app', 'root_file', 'url_seed', 'unm_seed', 'url_token', 'unm_token', 'access_token', 'init_key', 'sync_key', 'token_hash', 'sync_token_hash'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        //$query = Client::find();
		$query = Client::find()->where(['user_id'=>Yii::$app->user->identity->id])->joinWith(['tipe','status']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$dataProvider->sort->attributes['tipe'] = [
			'asc' => ['tipe.tipe_app' => SORT_ASC],
			'desc' => ['tipe.tipe_app' => SORT_DESC],
		];
		
		$dataProvider->sort->attributes['status'] = [
			'asc' => ['status.status' => SORT_ASC],
			'desc' => ['status.status' => SORT_DESC],
		];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tipe_id' => $this->tipe_id,
            'user_id' => $this->user_id,
            'status_id' => $this->status_id,
            'seq_num' => $this->seq_num,
            'access_token_t' => $this->access_token_t,
            'access_token_to' => $this->access_token_to,
            'init_key_t' => $this->init_key_t,
            'init_key_to' => $this->init_key_to,
            'sync_key_t' => $this->sync_key_t,
            'sync_key_to' => $this->sync_key_to,
            'token_hash_t' => $this->token_hash_t,
            'token_hash_to' => $this->token_hash_to,
            'sync_token_hash_t' => $this->sync_token_hash_t,
            'sync_token_hash_to' => $this->sync_token_hash_to,
        ]);

        $query->andFilterWhere(['like', 'nama_app', $this->nama_app])
            ->andFilterWhere(['like', 'tipe.tipe_app', $this->tipe])
            ->andFilterWhere(['like', 'status.status', $this->status])
            ->andFilterWhere(['like', 'root_file', $this->root_file])
            ->andFilterWhere(['like', 'url_seed', $this->url_seed])
            ->andFilterWhere(['like', 'unm_seed', $this->unm_seed])
            ->andFilterWhere(['like', 'url_token', $this->url_token])
            ->andFilterWhere(['like', 'unm_token', $this->unm_token])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'init_key', $this->init_key])
            ->andFilterWhere(['like', 'sync_key', $this->sync_key])
            ->andFilterWhere(['like', 'token_hash', $this->token_hash])
            ->andFilterWhere(['like', 'sync_token_hash', $this->sync_token_hash]);

        return $dataProvider;
    }
}
