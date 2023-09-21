<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "notes".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $status
 * @property int $position
 * @property int $date_created
 * @property int|null $date_updated
 * @property int|null $date_execute
 * @property int|null $date_started
 * @property int $user_id
 */
class Notes extends \yii\db\ActiveRecord
{
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
            [['title', 'description', 'position', 'date_created', 'user_id'], 'required'],
            [['title', 'description'], 'string'],
            [['status', 'position', 'date_created', 'date_updated', 'date_execute', 'date_started', 'user_id'], 'integer'],
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
            'user_id' => 'User ID',
        ];
    }
}
