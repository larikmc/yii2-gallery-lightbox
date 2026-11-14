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

        return $this->render('@larikmc/lightbox/views/lightbox', [
            'id' => $this->id,
            'title' => $this->title,
            'items' => $this->items,
            'itemsJson' => $itemsJson,
            'renderGrid' => $this->renderGrid,
            'gridClass' => $this->gridClass,
            'options' => $this->options,
            'clientOptions' => $this->clientOptions,
        ]);
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
