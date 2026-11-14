<?php

namespace larikmc\lightbox;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * LightboxWidget renders a high-performance fullscreen lightbox gallery modal.
 *
 * Usage example:
 * ```php
 * <?= \larikmc\lightbox\LightboxWidget::widget([
 *     'id' => 'myLightbox',
 *     'items' => [
 *         ['thumbnail' => '/img/thumb1.jpg', 'full' => '/img/full1.jpg', 'title' => 'Screenshot 1'],
 *         ['thumbnail' => '/img/thumb2.jpg', 'full' => '/img/full2.jpg', 'title' => 'Screenshot 2'],
 *     ],
 *     'title' => 'Gallery Title',
 *     'loaderType' => 'arrows', // 'circle', 'arrows', 'dots', 'none'
 *     'arrowOptions' => [
 *         'hoverBg' => '#6366f1',
 *         'hoverColor' => '#ffffff',
 *         'size' => 48,
 *     ],
 * ]) ?>
 * ```
 */
class LightboxWidget extends Widget
{
    /**
     * @var string HTML ID of the lightbox modal container
     */
    public string $id = 'plGalleryLightbox';

    /**
     * @var array Array of items: [['thumbnail' => '...', 'full' => '...', 'title' => '...'], ...]
     * Can also be a flat list of URL strings.
     */
    public array $items = [];

    /**
     * @var string Optional title displayed in the lightbox header
     */
    public string $title = '';

    /**
     * @var bool Whether to render a gallery thumbnail grid above/inside the container
     */
    public bool $renderGrid = false;

    /**
     * @var string CSS class for the thumbnail grid container
     */
    public string $gridClass = 'pl-lightbox-grid';

    /**
     * @var string Preloader spinner type: 'circle', 'arrows', 'dots', 'none'
     */
    public string $loaderType = 'circle';

    /**
     * @var string|null Custom HTML for the loader spinner (overrides $loaderType)
     */
    public ?string $loaderHtml = null;

    /**
     * @var string|null Accent color (e.g. '#6366f1', '#a855f7') for active border and highlights
     */
    public ?string $accentColor = null;

    /**
     * @var array Configuration options for navigation arrows:
     * - 'color': default icon color
     * - 'hoverColor': icon color on hover
     * - 'bg': button background color
     * - 'hoverBg': button background on hover
     * - 'borderColor': border color
     * - 'hoverBorderColor': border color on hover
     * - 'size': diameter in px (e.g. 48)
     * - 'shape': 'circle' (default), 'rounded' (border-radius 10px), 'square'
     * - 'prevIcon': custom HTML/SVG for previous arrow
     * - 'nextIcon': custom HTML/SVG for next arrow
     * - 'class': additional CSS classes for arrow buttons
     */
    public array $arrowOptions = [];

    /**
     * @var array Custom HTML attributes for the modal backdrop
     */
    public array $options = [];

    /**
     * @var array Client-side configuration options for JavaScript
     */
    public array $clientOptions = [
        'enableKeyboard' => true,
        'enableTouchSwipe' => true,
        'closeOnBackdrop' => true,
        'loop' => true,
        'showThumbStrip' => true,
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        if (empty($this->id)) {
            $this->id = 'plGalleryLightbox_' . $this->getId();
        }

        // Normalize items array
        $this->items = $this->normalizeItems($this->items);

        // Normalize arrowOptions
        $this->arrowOptions = array_merge([
            'color' => null,
            'hoverColor' => null,
            'bg' => null,
            'hoverBg' => null,
            'borderColor' => null,
            'hoverBorderColor' => null,
            'size' => null,
            'shape' => 'circle',
            'prevIcon' => null,
            'nextIcon' => null,
            'class' => '',
        ], $this->arrowOptions);
    }

    /**
     * @inheritdoc
     */
    public function run(): string
    {
        $view = $this->getView();
        LightboxAsset::register($view);

        $clientOptionsJson = Json::encode($this->clientOptions);
        $itemsJson = Json::encode($this->items);

        $js = "if (window.GalleryLightbox) { window.GalleryLightbox.initModal('{$this->id}', {$clientOptionsJson}); }";
        $view->registerJs($js);

        // Build scoped CSS variables for colors, arrows, and accent
        $cssVars = $this->buildScopedCssVars();
        $options = $this->options;
        if (!empty($cssVars)) {
            $existingStyle = $options['style'] ?? '';
            $options['style'] = implode('; ', $cssVars) . '; ' . $existingStyle;
        }

        return $this->render('@larikmc/lightbox/views/lightbox', [
            'id' => $this->id,
            'title' => $this->title,
            'items' => $this->items,
            'itemsJson' => $itemsJson,
            'renderGrid' => $this->renderGrid,
            'gridClass' => $this->gridClass,
            'loaderType' => $this->loaderType,
            'loaderHtml' => $this->loaderHtml,
            'accentColor' => $this->accentColor,
            'arrowOptions' => $this->arrowOptions,
            'options' => $options,
            'clientOptions' => $this->clientOptions,
        ]);
    }

    /**
     * Builds scoped CSS variables from widget configuration.
     *
     * @return array
     */
    protected function buildScopedCssVars(): array
    {
        $vars = [];

        if (!empty($this->accentColor)) {
            $vars[] = '--pl-lb-accent: ' . $this->accentColor;
            $vars[] = '--pl-lb-accent-glow: ' . $this->colorToRgbaGlow($this->accentColor);
        }

        if (!empty($this->arrowOptions['color'])) {
            $vars[] = '--pl-lb-arrow-color: ' . $this->arrowOptions['color'];
        }
        if (!empty($this->arrowOptions['hoverColor'])) {
            $vars[] = '--pl-lb-arrow-hover-color: ' . $this->arrowOptions['hoverColor'];
        }
        if (!empty($this->arrowOptions['bg'])) {
            $vars[] = '--pl-lb-arrow-bg: ' . $this->arrowOptions['bg'];
        }
        if (!empty($this->arrowOptions['hoverBg'])) {
            $vars[] = '--pl-lb-arrow-hover-bg: ' . $this->arrowOptions['hoverBg'];
        }
        if (!empty($this->arrowOptions['borderColor'])) {
            $vars[] = '--pl-lb-arrow-border: ' . $this->arrowOptions['borderColor'];
        }
        if (!empty($this->arrowOptions['hoverBorderColor'])) {
            $vars[] = '--pl-lb-arrow-hover-border: ' . $this->arrowOptions['hoverBorderColor'];
        }
        if (!empty($this->arrowOptions['size'])) {
            $size = is_numeric($this->arrowOptions['size']) ? $this->arrowOptions['size'] . 'px' : $this->arrowOptions['size'];
            $vars[] = '--pl-lb-arrow-size: ' . $size;
        }
        if (!empty($this->arrowOptions['shape'])) {
            $radius = match ($this->arrowOptions['shape']) {
                'square' => '0px',
                'rounded' => '10px',
                default => '50%',
            };
            $vars[] = '--pl-lb-arrow-radius: ' . $radius;
        }

        return $vars;
    }

    /**
     * Converts a HEX color to an RGBA glow string.
     *
     * @param string $color
     * @param float $alpha
     * @return string
     */
    protected function colorToRgbaGlow(string $color, float $alpha = 0.4): string
    {
        $hex = ltrim($color, '#');
        if (strlen($hex) === 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
            return "rgba({$r}, {$g}, {$b}, {$alpha})";
        } elseif (strlen($hex) === 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            return "rgba({$r}, {$g}, {$b}, {$alpha})";
        }
        return 'rgba(99, 102, 241, 0.4)';
    }

    /**
     * Normalizes items into a consistent structure.
     *
     * @param array $rawItems
     * @return array
     */
    protected function normalizeItems(array $rawItems): array
    {
        $normalized = [];
        foreach ($rawItems as $item) {
            if (is_string($item)) {
                $normalized[] = [
                    'thumbnail' => $item,
                    'full' => $item,
                    'title' => '',
                ];
            } elseif (is_array($item)) {
                $thumb = $item['thumbnail'] ?? $item['thumb'] ?? $item['preview'] ?? $item['full'] ?? '';
                $full = $item['full'] ?? $item['src'] ?? $item['url'] ?? $thumb;
                $title = $item['title'] ?? $item['caption'] ?? $item['alt'] ?? '';

                if (!empty($full) || !empty($thumb)) {
                    $normalized[] = [
                        'thumbnail' => $thumb ?: $full,
                        'full' => $full ?: $thumb,
                        'title' => (string)$title,
                    ];
                }
            }
        }
        return $normalized;
    }
}
