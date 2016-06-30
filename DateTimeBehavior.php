<?php

namespace ejen\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FormatConverter;

class DateTimeBehavior extends \yii\behaviors\AttributeBehavior
{
    public $sourceFormat = "php:d/m/Y";
    public $destinationFormat = "php:Y-m-d H:i:s";

    public function init()
    {
        parent::init();

        if (!empty($this->attributes))
        {
            $this->attributes = [
                ActiveRecord::EVENT_BEFORE_INSERT => $this->attributes,
                ActiveRecord::EVENT_BEFORE_UPDATE => $this->attributes,
            ];
        }
    }

    public function getValue($event)
    {
        $value = $event->sender->license_issue;
        if (empty($value)) return null;

        if (strncmp($this->sourceFormat, 'php:', 4) === 0)
        {
            $sourceFormat = FormatConverter::convertDatePhpToIcu(substr($this->sourceFormat, 4));
        }
        else
        {
            $sourceFormat = $this->sourceFormat;
        } 

        $formatter = new \IntlDateFormatter(Yii::$app->formatter->locale, null, null, Yii::$app->formatter->timeZone, Yii::$app->formatter->calendar, $sourceFormat);
        return Yii::$app->formatter->asDateTime($formatter->parse($value), $this->destinationFormat);
    }
}
