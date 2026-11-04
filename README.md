# Beau & Co WordPress Theme

A custom WordPress theme built for a business directory and lifestyle publication, with WooCommerce support and a modern component-based frontend.

## Built With

- WordPress and WooCommerce
- PHP
- ACF blocks and fields
- Tailwind CSS
- Vite
- Alpine.js
- GSAP

## Features

- Custom post types for businesses, careers, events, projects, and spotlights
- Custom WooCommerce templates and product functionality
- ACF-powered content blocks and theme settings
- Custom account, wishlist, login, and registration templates
- HubSpot form integration
- Responsive custom navigation
- Vite-based asset build pipeline

## ACF Blocks

The registered block names, titles, icons, and render templates are defined in [app/Services/BlockRegistrar.php](./app/Services/BlockRegistrar.php).

Each block template lives in [blocks/](./blocks/). For example, the [content block template](./blocks/content-block.php) works together with its field definition in the matching JSON file under [acf-json/](./acf-json/).

Themes can register additional blocks by adding an entry to `BlockRegistrar::getBlocks()` and placing the corresponding template in `blocks/<block-name>.php`.

## Development

```bash
npm install
npm run dev
composer install
composer dump-autoload
```

Create a production build with:

```bash
npm run build
```

## Installation

The theme requires WooCommerce and Advanced Custom Fields.
