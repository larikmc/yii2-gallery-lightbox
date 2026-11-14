<?php

namespace larikmc\lightbox;

use yii\web\AssetBundle;

/**
 * Asset bundle for Yii2 Gallery Lightbox.
 * Zero-dependency: pure Vanilla JS and modern CSS.
 */
class LightboxAsset extends AssetBundle
{
    public $sourcePath = '@larikmc/lightbox/assets';

    public $css = [
        'css/lightbox.css',
    ];

    public $js = [
        'js/lightbox.js',
    ];

    public $depends = [
        // Intentionally empty to guarantee zero overhead / zero external dependencies.
    ];
}
