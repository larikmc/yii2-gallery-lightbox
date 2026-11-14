<?php

namespace larikmc\lightbox;

use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Extension bootstrap class for Yii2 Gallery Lightbox.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        // Set alias for assets and views if not already defined
        if (!\Yii::getAlias('@larikmc/lightbox', false)) {
            \Yii::setAlias('@larikmc/lightbox', __DIR__);
        }
    }
}
