<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "notes".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $status
 * @property int|null $position
 * @property int|null $date_created
 * @property int|null $date_updated
 * @property int|null $date_execute
 * @property int|null $date_started
 * @property int|null $user_id
 */
class Notes extends \yii\db\ActiveRecord
{
    public $time;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_created',
                'updatedAtAttribute' => 'date_updated',
                'value' => time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'time'], 'string'],
            [['status', 'position', 'date_created', 'date_updated', 'date_execute', 'date_started'], 'integer'],
            [['date_created', 'date_updated'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'position' => 'Position',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'date_execute' => 'Date Execute',
            'date_started' => 'Date Started',
        ];
    }
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (isset(Yii::$app->request->post()['Notes']['time'])) {
            if (Yii::$app->request->post()['Notes']['time'] != '') {
                $this->date_started = strtotime(Yii::$app->request->post()['Notes']['time']);
            } else {
                $this->date_started = null;
            }
        }
        
        $this->user_id = Yii::$app->user->id;

        return true;
    }
}