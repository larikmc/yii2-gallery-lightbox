# Yii2 Gallery Lightbox

[![Latest Stable Version](https://img.shields.io/badge/version-1.2.0-blue.svg)](https://github.com/larikmc/yii2-gallery-lightbox)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.x-purple.svg)](https://php.net)

A high-performance, modern, zero-dependency Vanilla JS gallery lightbox and fullscreen image viewer for Yii2 applications.

Designed with glassmorphism aesthetics, intelligent background image prefetching, smooth animations, touch/swipe gestures, thumbnail strip navigation, customizable navigation arrows, and selectable preloader spinners.

---

## ✨ Features

- **Zero Dependencies:** Pure Vanilla JavaScript (0 KB jQuery, no third-party lightbox libraries).
- **Background Prefetching (Instant Image Flipping):** Automatically preloads adjacent images in the background (default: 3 ahead, 1 behind) so flipping slides feels instantaneous even on slower CDNs or large 4K screenshots.
- **Glassmorphism & Clean Canvas:** Smooth, uniform dark backdrop with no artificial gradient banding or heavy edge vignettes.
- **Selectable Loaders / Spinners:** Choose between rotating dual arrows (`arrows`), sleek neon ring (`circle`), pulsing dots (`dots`), or your own custom HTML.
- **Customizable Arrows:** Change arrow hover background, icon color, button size, shape (`circle`, `rounded`, `square`), borders, or provide custom SVG icons.
- **CSS Variables Architecture:** Every color, size, and effect can be adjusted via PHP widget properties or CSS variables.
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
    "larikmc/yii2-gallery-lightbox": "^1.2"
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
    'preloadCount' => 3, // Preload 3 adjacent images in background
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

---

## ⚡ Background Prefetching (Adjacent Preload)

When browsing remote images (e.g. TMDB, Steam, or cloud storage), waiting for each high-resolution image to download can cause noticeable delays.

The lightbox includes built-in background prefetching that starts buffering the next images as soon as a slide is opened:

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'fastGallery',
    'items' => $items,
    'preloadCount' => 3, // Prefetch next 3 full-size images in background
    'preloadPrev' => 1,  // Prefetch previous 1 full-size image in background
]) ?>
```

When the user clicks **Next** or **Previous**, the high-res image is already stored in browser memory, resulting in instant transitions without spinner delay.

---

## 🎨 Customizing Navigation Arrows

You can configure arrow styling directly through the `arrowOptions` parameter:

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'customArrowsLightbox',
    'items' => $screenshots,
    'arrowOptions' => [
        // Colors & Hover
        'bg' => 'rgba(15, 23, 42, 0.7)',       // Default button background
        'hoverBg' => '#8b5cf6',                 // Background on hover (purple)
        'color' => '#ffffff',                   // Arrow icon color
        'hoverColor' => '#ffffff',              // Arrow icon color on hover
        'borderColor' => 'rgba(255,255,255,0.15)', // Default border
        'hoverBorderColor' => 'transparent',    // Border on hover

        // Geometry & Shape
        'size' => 52,                           // Button diameter in px (e.g. 48, 52)
        'shape' => 'rounded',                   // 'circle' (50%), 'rounded' (10px), or 'square' (0px)

        // Custom SVG icons (optional)
        'prevIcon' => '<svg ...><polyline points="15 18 9 12 15 6"></polyline></svg>',
        'nextIcon' => '<svg ...><polyline points="9 18 15 12 9 6"></polyline></svg>',

        // Additional CSS class
        'class' => 'my-custom-arrows',
    ],
]) ?>
```

### Arrow Configuration Options

| Option | Type | Default | Description |
|---|---|---|---|
| `bg` | `string` | `rgba(15, 23, 42, 0.6)` | Button background color. |
| `hoverBg` | `string` | `var(--pl-lb-accent)` | Button background color on hover. |
| `color` | `string` | `#ffffff` | Arrow icon stroke/fill color. |
| `hoverColor` | `string` | `#ffffff` | Arrow icon stroke/fill color on hover. |
| `borderColor` | `string` | `rgba(255, 255, 255, 0.15)` | Button border color. |
| `hoverBorderColor` | `string` | `transparent` | Button border color on hover. |
| `size` | `int\|string` | `48` | Width and height of navigation buttons (in px). |
| `shape` | `string` | `'circle'` | Shape of button: `'circle'` (round), `'rounded'` (10px radius), `'square'`. |
| `prevIcon` | `string\|null` | `null` | Custom HTML/SVG for previous button. |
| `nextIcon` | `string\|null` | `null` | Custom HTML/SVG for next button. |
| `class` | `string` | `''` | Extra CSS class names for arrow buttons. |

---

## ⏳ Preloader / Loader Styles

Choose how the loading spinner looks while full-size images are buffering into memory:

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'customLoaderLightbox',
    'items' => $screenshots,
    'loaderType' => 'arrows', // 'arrows' | 'circle' | 'dots' | 'none'
]) ?>
```

### Available Preloader Types

| Value | Preview / Behavior | Description |
|---|---|---|
| `'circle'` *(default)* | Sleek Neon Ring | Modern circular spinner with spinning accent border. |
| `'arrows'` | Rotating Dual Arrows | Dynamic reload/refresh spinning arrows (as used in game screenshot viewer). |
| `'dots'` | Pulsing Triple Dots | Three smooth glowing dots with scale & opacity pulse. |
| `'none'` | Hidden | Disables preloader indicator. |

### Custom Preloader Markup

If you have a custom spinner component or Lottie animation, pass it via `loaderHtml`:

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'customHtmlLoaderLightbox',
    'items' => $screenshots,
    'loaderHtml' => '<div class="my-custom-spinner"><i class="bi bi-arrow-repeat spin"></i> Loading...</div>',
]) ?>
```

---

## 🌈 Accent Color

Set the primary accent color for active thumbnail borders, focus glow, and default hover states:

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'brandLightbox',
    'items' => $screenshots,
    'accentColor' => '#10b981', // Emerald green
]) ?>
```

---

## 🎛️ CSS Variables Reference

All components are styled using CSS Custom Properties. You can override them globally in your stylesheet or per container:

```css
/* Custom theme for gallery */
#myMovieGallery {
    --pl-lb-bg: rgba(10, 14, 26, 0.96);   /* Solid or glass backdrop */
    --pl-lb-accent: #f43f5e;              /* Accent color (rose) */
    --pl-lb-accent-glow: rgba(244, 63, 94, 0.4);
    --pl-lb-arrow-size: 52px;             /* Larger buttons */
    --pl-lb-arrow-radius: 12px;           /* Rounded squares */
    --pl-lb-arrow-bg: rgba(0, 0, 0, 0.7); /* Darker backdrop */
    --pl-lb-arrow-hover-bg: #f43f5e;      /* Rose hover */
    --pl-lb-arrow-hover-color: #ffffff;
}
```

---

## ⚙️ Full Widget Options Table

| Option | Type | Default | Description |
|---|---|---|---|
| `id` | `string` | `'plGalleryLightbox'` | Unique HTML ID for the modal. |
| `items` | `array` | `[]` | Array of items (`['thumbnail' => '...', 'full' => '...', 'title' => '...']`) or array of URL strings. |
| `title` | `string` | `''` | Gallery title displayed in the top toolbar. |
| `renderGrid` | `bool` | `false` | If `true`, automatically renders a responsive thumbnail grid above the modal. |
| `gridClass` | `string` | `'pl-lightbox-grid'` | CSS class applied to the generated grid container. |
| `loaderType` | `string` | `'circle'` | Preloader type: `'circle'`, `'arrows'`, `'dots'`, `'none'`. |
| `loaderHtml` | `string\|null`| `null` | Custom HTML markup for the preloader. |
| `accentColor`| `string\|null`| `null` | Custom accent HEX color (e.g. `'#6366f1'`). |
| `arrowOptions`| `array` | `[]` | Navigation arrow configuration array. |
| `preloadCount`| `int` | `3` | Number of adjacent images to prefetch ahead in background. |
| `preloadPrev` | `int` | `1` | Number of adjacent images to prefetch behind in background. |
| `clientOptions`| `array` | `[...]` | Client options passed to JavaScript. |

---

## 💻 Programmatic JavaScript API

You can open, update, and control the gallery dynamically from JavaScript:

```javascript
// Open with pre-configured items at index 0
GalleryLightbox.open('gameLightbox', 0);

// Open on-the-fly with dynamic JSON items (e.g. from AJAX):
GalleryLightbox.open('gameLightbox', 0, [
    { thumbnail: 'https://.../t1.jpg', full: 'https://.../f1.jpg' },
    { thumbnail: 'https://.../t2.jpg', full: 'https://.../f2.jpg' }
], 'The Matrix (1999)');

// Navigation controls:
GalleryLightbox.next('gameLightbox');
GalleryLightbox.prev('gameLightbox');
GalleryLightbox.close('gameLightbox');
```

---

## 📄 License

This extension is open-sourced software licensed under the [MIT license](LICENSE.md).

Created by **LarikMC** ([https://64web.ru](https://64web.ru)).
