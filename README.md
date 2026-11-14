# Yii2 Gallery Lightbox

[![Latest Stable Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/larikmc/yii2-gallery-lightbox)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.x-purple.svg)](https://php.net)

A high-performance, modern, zero-dependency Vanilla JS gallery lightbox and fullscreen image viewer for Yii2 applications.

Designed with glassmorphism aesthetics, smooth animations, touch/swipe gestures, thumbnail strip navigation, and dark/light mode compatibility.

---

## ✨ Features

- **Zero Dependencies:** Pure Vanilla JavaScript (0 KB jQuery, no third-party lightbox libraries).
- **Glassmorphism Design:** Modern backdrop blur and dark/light theme aware styles.
- **Touch & Mobile Gestures:** Swipe left/right to navigate, swipe down to close.
- **Keyboard Navigation:** `←` / `→` arrows to navigate with continuous loop, `Esc` to close.
- **Smart Preloader:** Smooth image transition only after image is fully buffered in memory with thumbnail fallback.
- **Interactive Thumbnails Strip:** Bottom scrollable thumbnail previews with auto-centering on active item.
- **Flexible Usage:** Use as a Yii2 PHP Widget, HTML data-attributes (`data-gallery-lightbox`), or programmatic JavaScript API (`window.GalleryLightbox`).

---

## 📦 Installation

Add repository and package to your `composer.json`:

```json
"require": {
    "larikmc/yii2-gallery-lightbox": "^1.0"
}
```

Or install via composer:

```bash
composer require larikmc/yii2-gallery-lightbox
```

---

## 🚀 Quick Start

### 1. Simple Widget with Thumbnail Grid

```php
use larikmc\lightbox\LightboxWidget;

<?= LightboxWidget::widget([
    'id' => 'myMovieGallery',
    'title' => 'Interstellar (2014) - Stills',
    'renderGrid' => true,
    'items' => [
        [
            'thumbnail' => 'https://wsrv.nl/?url=.../thumb1.jpg',
            'full' => 'https://wsrv.nl/?url=.../full1.jpg',
            'title' => 'Black Hole Gargantua',
        ],
        [
            'thumbnail' => 'https://wsrv.nl/?url=.../thumb2.jpg',
            'full' => 'https://wsrv.nl/?url=.../full2.jpg',
            'title' => 'Water Planet',
        ],
    ],
]) ?>
```

### 2. Standalone Modal Triggered by Custom Elements

Render the lightbox container once in your view:

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'gameLightbox',
    'title' => $game->title,
    'items' => $screenshots,
]) ?>
```

Trigger it from any HTML element with `data-gallery-lightbox`:

```html
<!-- Opens screenshot at index 0 -->
<button data-gallery-lightbox="gameLightbox" data-index="0">
    View Gallery
</button>

<!-- Or trigger via any image -->
<img src="thumb.jpg" data-gallery-lightbox="gameLightbox" data-index="3" alt="Frame">
```

### 3. Programmatic JavaScript API

You can open, update, and control the gallery dynamically from JavaScript:

```javascript
// Open with pre-configured items at index 0
GalleryLightbox.open('gameLightbox', 0);

// Open on-the-fly with dynamic JSON items (e.g. from AJAX):
GalleryLightbox.open('gameLightbox', 0, [
    { thumbnail: 'https://.../t1.jpg', full: 'https://.../f1.jpg' },
    { thumbnail: 'https://.../t2.jpg', full: 'https://.../f2.jpg' }
], 'Baldur\'s Gate 3');

// Navigation controls:
GalleryLightbox.next('gameLightbox');
GalleryLightbox.prev('gameLightbox');
GalleryLightbox.close('gameLightbox');
```

---

## ⚙️ Widget Options

| Option | Type | Default | Description |
|---|---|---|---|
| `id` | `string` | `'plGalleryLightbox'` | Unique HTML ID for the modal. |
| `items` | `array` | `[]` | Array of items (`['thumbnail' => '...', 'full' => '...', 'title' => '...']`) or array of URL strings. |
| `title` | `string` | `''` | Gallery title displayed in the top toolbar. |
| `renderGrid` | `bool` | `false` | If `true`, automatically renders a responsive thumbnail grid above the modal. |
| `gridClass` | `string` | `'pl-lightbox-grid'` | CSS class applied to the generated grid container. |
| `clientOptions` | `array` | `[...]` | Client options passed to JavaScript (see below). |

### Client Options

```php
'clientOptions' => [
    'enableKeyboard' => true,    // Arrow Left / Right and Esc
    'enableTouchSwipe' => true,  // Touch swipes on mobile
    'closeOnBackdrop' => true,   // Click on backdrop closes modal
    'loop' => true,              // Wrap around at first/last slide
    'showThumbStrip' => true,    // Display bottom thumbnail strip
]
```

---

## 📄 License

This extension is open-sourced software licensed under the [MIT license](LICENSE.md).

Created by **LarikMC** ([https://64web.ru](https://64web.ru)).
