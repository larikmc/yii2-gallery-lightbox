<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string $id
 * @var string $title
 * @var array $items
 * @var string $itemsJson
 * @var bool $renderGrid
 * @var string $gridClass
 * @var string $loaderType
 * @var string|null $loaderHtml
 * @var string|null $loaderColor
 * @var string|null $accentColor
 * @var array $arrowOptions
 * @var array $options
 * @var array $clientOptions
 */

$backdropOptions = array_merge([
    'id' => $id,
    'class' => 'pl-lightbox-backdrop',
    'role' => 'dialog',
    'aria-modal' => 'true',
    'aria-hidden' => 'true',
    'style' => 'display: none;',
], $options);

if (!empty($options['class'])) {
    $backdropOptions['class'] = 'pl-lightbox-backdrop ' . $options['class'];
}

$arrowExtraClass = !empty($arrowOptions['class']) ? ' ' . Html::encode($arrowOptions['class']) : '';
$iconType = $arrowOptions['iconType'] ?? 'chevron';

// Generate Prev Icon
if (!empty($arrowOptions['prevIcon'])) {
    $prevIconHtml = $arrowOptions['prevIcon'];
} elseif ($iconType === 'arrow') {
    $prevIconHtml = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>';
} elseif ($iconType === 'caret') {
    $prevIconHtml = '<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><polygon points="16,5 7,12 16,19"></polygon></svg>';
} else {
    $prevIconHtml = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
}

// Generate Next Icon
if (!empty($arrowOptions['nextIcon'])) {
    $nextIconHtml = $arrowOptions['nextIcon'];
} elseif ($iconType === 'arrow') {
    $nextIconHtml = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
} elseif ($iconType === 'caret') {
    $nextIconHtml = '<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><polygon points="8,5 17,12 8,19"></polygon></svg>';
} else {
    $nextIconHtml = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
}
?>

<?php if ($renderGrid && !empty($items)): ?>
    <div class="<?= Html::encode($gridClass) ?>" data-gallery-id="<?= Html::encode($id) ?>">
        <?php foreach ($items as $idx => $item): ?>
            <div class="pl-lightbox-grid-item" data-gallery-lightbox="<?= Html::encode($id) ?>" data-index="<?= $idx ?>">
                <img src="<?= Html::encode($item['thumbnail']) ?>" 
                     alt="<?= Html::encode($item['title'] ?: ($title . ' - Image ' . ($idx + 1))) ?>" 
                     loading="lazy" 
                     class="pl-lightbox-grid-img">
                <div class="pl-lightbox-grid-overlay">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <polyline points="9 21 3 21 3 15"></polyline>
                        <line x1="21" y1="3" x2="14" y2="10"></line>
                        <line x1="3" y1="21" x2="10" y2="14"></line>
                    </svg>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= Html::beginTag('div', $backdropOptions) ?>
    <div class="pl-lightbox-container">
        <!-- Top Toolbar -->
        <div class="pl-lightbox-toolbar">
            <div class="pl-lightbox-info">
                <span class="pl-lightbox-title" id="<?= $id ?>_title"><?= Html::encode($title) ?></span>
                <span class="pl-lightbox-counter" id="<?= $id ?>_counter">
                    <?= !empty($items) ? '1 / ' . count($items) : '' ?>
                </span>
            </div>
            <div class="pl-lightbox-actions">
                <a href="#" id="<?= $id ?>_directLink" target="_blank" rel="noopener noreferrer" class="pl-lightbox-btn" title="<?= Yii::t('app', 'Прямая ссылка') ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                </a>
                <button type="button" class="pl-lightbox-btn pl-lightbox-close" id="<?= $id ?>_closeBtn" aria-label="<?= Yii::t('app', 'Закрыть') ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Main Slide Viewport -->
        <div class="pl-lightbox-stage">
            <button type="button" class="pl-lightbox-nav-btn pl-lightbox-prev<?= $arrowExtraClass ?>" id="<?= $id ?>_prevBtn" aria-label="<?= Yii::t('app', 'Назад') ?>">
                <?= $prevIconHtml ?>
            </button>

            <!-- Centered Preloader Spinner -->
            <div class="pl-lightbox-loader pl-loader-<?= Html::encode($loaderType) ?>" id="<?= $id ?>_loader" style="display: none;">
                <?php if (!empty($loaderHtml)): ?>
                    <?= $loaderHtml ?>
                <?php elseif ($loaderType === 'arrows' || $loaderType === 'arrows-wheel'): ?>
                    <div class="pl-lightbox-loader-wheel">
                        <svg class="pl-spin" width="34" height="34" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                        </svg>
                    </div>
                <?php elseif ($loaderType === 'circle' || $loaderType === 'ring'): ?>
                    <div class="pl-lightbox-spinner-circle"></div>
                <?php elseif ($loaderType === 'dual-ring' || $loaderType === 'orbit'): ?>
                    <div class="pl-lightbox-spinner-dual"></div>
                <?php elseif ($loaderType === 'pulse' || $loaderType === 'radar'): ?>
                    <div class="pl-lightbox-spinner-pulse">
                        <div class="pl-pulse-ring"></div>
                        <div class="pl-pulse-dot"></div>
                    </div>
                <?php elseif ($loaderType === 'dots' || $loaderType === 'pulse-dots'): ?>
                    <div class="pl-lightbox-spinner-dots">
                        <span></span><span></span><span></span>
                    </div>
                <?php elseif ($loaderType === 'bars' || $loaderType === 'equalizer'): ?>
                    <div class="pl-lightbox-spinner-bars">
                        <span></span><span></span><span></span><span></span>
                    </div>
                <?php elseif ($loaderType === 'none'): ?>
                    <!-- Loader disabled -->
                <?php else: ?>
                    <div class="pl-lightbox-loader-wheel">
                        <svg class="pl-spin" width="34" height="34" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pl-lightbox-image-wrap">
                <img src="" id="<?= $id ?>_mainImg" alt="" class="pl-lightbox-img" style="display: none; opacity: 0;">
            </div>

            <button type="button" class="pl-lightbox-nav-btn pl-lightbox-next<?= $arrowExtraClass ?>" id="<?= $id ?>_nextBtn" aria-label="<?= Yii::t('app', 'Вперед') ?>">
                <?= $nextIconHtml ?>
            </button>
        </div>

        <!-- Bottom Thumbnails Strip -->
        <div class="pl-lightbox-strip-wrapper">
            <div class="pl-lightbox-strip" id="<?= $id ?>_thumbStrip">
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $idx => $item): ?>
                        <img src="<?= Html::encode($item['thumbnail']) ?>" 
                             alt="Thumb <?= $idx + 1 ?>" 
                             class="pl-lightbox-thumb <?= $idx === 0 ? 'is-active' : '' ?>" 
                             data-index="<?= $idx ?>">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Embedded Initial Data -->
    <script id="<?= $id ?>_data" type="application/json"><?= $itemsJson ?></script>
<?= Html::endTag('div') ?>
