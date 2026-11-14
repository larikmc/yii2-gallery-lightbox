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
                    <i class="bi bi-arrows-fullscreen"></i>
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
                <a href="#" id="<?= $id ?>_directLink" target="_blank" rel="noopener noreferrer" class="pl-lightbox-btn" title="<?= Yii::t('app', 'Открыть оригинал') ?>">
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
            <button type="button" class="pl-lightbox-nav-btn pl-lightbox-prev" id="<?= $id ?>_prevBtn" aria-label="<?= Yii::t('app', 'Предыдущий') ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <!-- Centered Rotating Spinner -->
            <div class="pl-lightbox-loader" id="<?= $id ?>_loader" style="display: none;">
                <div class="pl-lightbox-spinner"></div>
            </div>

            <div class="pl-lightbox-image-wrap">
                <img src="" id="<?= $id ?>_mainImg" alt="" class="pl-lightbox-img" style="display: none; opacity: 0;">
            </div>

            <button type="button" class="pl-lightbox-nav-btn pl-lightbox-next" id="<?= $id ?>_nextBtn" aria-label="<?= Yii::t('app', 'Следующий') ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
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
