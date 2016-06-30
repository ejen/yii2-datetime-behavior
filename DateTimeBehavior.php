<?php

namespace ejen\behaviors;

class DateTimeBehavior extends \yii\behaviors\AttributeBehavior
{
    public $sourceFormat = "php:d/m/Y";
    public $destinationFormat = "php:Y-m-d H:i:s";

    public function init()
    {
        parent::init();
    }
}
