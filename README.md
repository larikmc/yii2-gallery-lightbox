# Yii2 Gallery Lightbox

[![Latest Stable Version](https://img.shields.io/badge/version-1.3.0-blue.svg)](https://github.com/larikmc/yii2-gallery-lightbox)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.x-purple.svg)](https://php.net)

A high-performance, modern, zero-dependency Vanilla JS gallery lightbox and fullscreen image viewer for Yii2 applications.

Designed with glassmorphism aesthetics, intelligent background image prefetching, 6 beautiful preloader spinner styles with button color synchronization, customizable navigation arrows, smooth gestures, and a deep cinema canvas.

---

## ✨ Features

- **Zero Dependencies:** Pure Vanilla JavaScript (0 KB jQuery, no third-party lightbox libraries).
- **Background Prefetching (Instant Image Flipping):** Automatically preloads adjacent images in the background (default: 3 ahead, 1 behind) so flipping slides feels instantaneous even on slower CDNs or large 4K screenshots.
- **Deep Cinema Canvas:** Smooth, uniform dark backdrop with no artificial gradient banding or blurry page artifacts.
- **6 Built-in Preloader Variants:** Choose between original rotating arrows wheel (`arrows`), sleek neon ring (`circle`), dual counter-rotating orbital rings (`dual-ring`), pulsing radar wave (`pulse`), glowing dots (`dots`), or equalizer bars (`bars`).
- **Synchronized Colors:** Spinners automatically inherit and match your navigation button hover and accent colors (`hoverBg` / `accentColor`).
- **Customizable Arrows & Icons:** Choose arrow icon types (`chevron`, `arrow`, `caret`), hover background, icon color, button size, shape (`circle`, `rounded`, `square`), borders, or custom SVG icons.
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
    "larikmc/yii2-gallery-lightbox": "^1.3"
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
    'loaderType' => 'arrows', // Rotating arrows inside glass badge
    'preloadCount' => 3,     // Preload 3 adjacent images in background
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

## ⏳ Preloader / Loader Styles

Choose how the loading spinner looks while full-size images are buffering into memory:

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'customLoaderLightbox',
    'items' => $screenshots,
    'loaderType' => 'arrows', // 'arrows' | 'circle' | 'dual-ring' | 'pulse' | 'dots' | 'bars' | 'none'
]) ?>
```

### Available Preloader Types

| Value | Appearance & Animation | Description |
|---|---|---|
| `'arrows'` *(original)* | **Frosted Wheel with Rotating Arrows** | The signature badge: 72px frosted glass disc with rotating dual arrows and accent drop-glow. |
| `'circle'` | **Neon Ring** | Sleek circular spinner with spinning accent border and glow. |
| `'dual-ring'` | **Orbital Rings** | Two concentric counter-rotating orbital glowing rings. |
| `'pulse'` | **Radar Wave** | Glowing center dot emitting expanding circular radar ripples. |
| `'dots'` | **Pulsing Dots** | Three wave-pulsing glowing dots with smooth scaling. |
| `'bars'` | **Equalizer Bars** | Four rhythmic glowing equalizer bars. |
| `'none'` | **Hidden** | Disables preloader indicator. |

### Color Synchronization

The loader **automatically synchronizes its color and glow** with your button settings:
- If you set `'arrowOptions' => ['hoverBg' => '#a855f7']`, the spinner automatically turns `#a855f7`!
- If you set `'accentColor' => '#10b981'`, both buttons and spinner turn emerald green!
- If you want a specific custom color for only the spinner, use `'loaderColor' => '#ffaa00'`.

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'syncedGallery',
    'items' => $screenshots,
    'loaderType' => 'dual-ring',
    // Changing button hover color automatically changes the loader color!
    'arrowOptions' => [
        'hoverBg' => '#8b5cf6', // Violet
    ],
]) ?>
```

---

## 🎨 Customizing Navigation Arrows

You can configure arrow styling directly through the `arrowOptions` parameter:

```php
<?= \larikmc\lightbox\LightboxWidget::widget([
    'id' => 'customArrowsLightbox',
    'items' => $screenshots,
    'arrowOptions' => [
        // Arrow Icon Style
        'iconType' => 'chevron',                // 'chevron' (< >), 'arrow' (← →), or 'caret' (◀ ▶)

        // Colors & Hover (also affects loader)
        'bg' => 'rgba(255, 255, 255, 0.08)',   // Default button background
        'hoverBg' => '#8b5cf6',                 // Background on hover
        'color' => '#ffffff',                   // Arrow icon color
        'hoverColor' => '#ffffff',              // Arrow icon color on hover
        'borderColor' => 'rgba(255,255,255,0.15)', // Default border
        'hoverBorderColor' => 'transparent',    // Border on hover

        // Geometry & Shape
        'size' => 52,                           // Button diameter in px (e.g. 48, 52)
        'shape' => 'circle',                    // 'circle' (50%), 'rounded' (10px), or 'square' (0px)

        // Custom SVG icons (optional)
        'prevIcon' => null,
        'nextIcon' => null,
        'class' => 'my-custom-arrows',
    ],
]) ?>
```

### Arrow Configuration Options

| Option | Type | Default | Description |
|---|---|---|---|
| `iconType` | `string` | `'chevron'` | Icon variant: `'chevron'`, `'arrow'`, or `'caret'`. |
| `bg` | `string` | `rgba(255, 255, 255, 0.08)` | Button background color. |
| `hoverBg` | `string` | `var(--pl-lb-accent)` | Button background on hover (also synchronizes with loader). |
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

## 🎛️ CSS Variables Reference

All components are styled using CSS Custom Properties. You can override them globally in your stylesheet or per container:

```css
#myMovieGallery {
    --pl-lb-bg: #090d16;                  /* Deep cinema canvas */
    --pl-lb-accent: #f43f5e;              /* Accent color (rose) */
    --pl-lb-accent-glow: rgba(244, 63, 94, 0.45);
    --pl-lb-arrow-size: 52px;             /* Larger buttons */
    --pl-lb-arrow-radius: 50%;            /* Round buttons */
    --pl-lb-arrow-hover-bg: #f43f5e;      /* Rose hover */
    --pl-lb-loader-color: #f43f5e;        /* Synchronized rose spinner */
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
| `loaderType` | `string` | `'arrows'` | Preloader type: `'arrows'`, `'circle'`, `'dual-ring'`, `'pulse'`, `'dots'`, `'bars'`, `'none'`. |
| `loaderHtml` | `string\|null`| `null` | Custom HTML markup for the preloader. |
| `loaderColor`| `string\|null`| `null` | Custom color for loader (defaults to button hoverBg / accentColor). |
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
