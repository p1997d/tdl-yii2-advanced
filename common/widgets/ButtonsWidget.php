<?php

namespace common\widgets;

use yii\helpers\Html;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class ButtonsWidget extends Widget
{
    public $buttons;
    public $id;
    public $name;
    public $defaultActive;
    public $activeButton;

    public function init()
    {
        parent::init();
        if ($this->buttons === null) {
            throw new InvalidConfigException('The "buttons" property must be set.');
        }
        if ($this->id === null) {
            throw new InvalidConfigException('The "id" property must be set.');
        }
        if ($this->name === null) {
            $this->name = '';
        }
        if ($this->activeButton === null) {
            if ($this->defaultActive === null) {
                $this->activeButton = '';
            } else {
                $this->activeButton = $this->defaultActive;
            }
        }
        foreach ($this->buttons as $button) {
            if (empty($button->name)) {
                throw new InvalidConfigException('The "button->name" property must be set.');
            }
            if (empty($button->id)) {
                throw new InvalidConfigException('The "button->id" property must be set.');
            }
            if (empty($button->value)) {
                $button->value = '';
            }
        }
    }
    public function run()
    {
        $output = '';
        $output .= Html::beginTag('div', ['class' => 'btn-group my-1 mx-1', 'role' => 'group', 'id' => $this->id]);
        foreach ($this->buttons as $button) {
            $output .= Html::submitButton($button->name, [
                'class' => 'btnWidget btn btn-sm ' . $this->id . (($this->activeButton === $button->value) ? ' active' : ''),
                'name' => $this->name,
                'value' => $button->value,
                'id' => $button->id,
            ]);
        }
        $output .= Html::endTag('div');

        return $output;
    }
}