<?php

namespace ejen\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FormatConverter;

class DateTimeBehavior extends \yii\behaviors\AttributeBehavior
{
    public $sourceFormat = "php:d/m/Y";
    public $destinationFormat = "php:Y-m-d H:i:s";

    public function events()
    {
        return [
            'beforeInsert' => 'evaluateAttributes',
            'beforeUpdate' => 'evaluateAttributes'
        ];
    }

    public function evaluateAttributes($event)
    {
        if (strncmp($this->sourceFormat, 'php:', 4) === 0)
        {
            $sourceFormat = FormatConverter::convertDatePhpToIcu(substr($this->sourceFormat, 4));
        }
        else
        {
            $sourceFormat = $this->sourceFormat;
        } 

        $formatter = new \IntlDateFormatter(Yii::$app->formatter->locale, null, null, Yii::$app->formatter->timeZone, Yii::$app->formatter->calendar, $sourceFormat);
        foreach ($this->attributes as $attribute)
        {
            $value = $this->owner->$attribute;
            if (empty($value)) continue;

            $this->owner->$attribute = Yii::$app->formatter->asDateTime($formatter->parse($value), $this->destinationFormat);
        }
    }
}
