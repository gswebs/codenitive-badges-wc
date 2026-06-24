# Codenitive Badges for WooCommerce

Display beautiful WooCommerce product attribute badges with custom colors, icons, and styling options.

## Features

* Display WooCommerce attribute terms as product badges
* Custom background color for each attribute term
* Custom text color for each attribute term
* Optional icon support
* Global badge styling options
* Automatic badge display on shop and product pages
* Lightweight and performance-friendly
* No theme modifications required

---

## Requirements

* WordPress 6.0+
* WooCommerce 7.0+
* PHP 7.4+

---

## Installation

### Install via WordPress Admin

1. Download the plugin ZIP file.
2. Navigate to **Plugins → Add New → Upload Plugin**.
3. Upload the ZIP file.
4. Activate the plugin.
5. Ensure WooCommerce is installed and activated.

### Manual Installation

1. Upload the plugin folder to:

```text
/wp-content/plugins/
```

2. Activate the plugin from the WordPress admin dashboard.

---

## Configuration

### Global Badge Settings

Navigate to:

```text
WooCommerce → Badge Settings
```

Configure:

* Default Badge Background Color
* Default Badge Text Color
* Border Radius
* Font Size

Save your settings.

---

### Attribute Term Settings

Navigate to:

```text
Products → Attributes
```

Edit any attribute term.

Additional fields will be available:

| Field                  | Description            |
| ---------------------- | ---------------------- |
| Badge Background Color | Badge background color |
| Badge Text Color       | Badge text color       |
| Badge Icon             | Optional icon or emoji |

Save the term.

---

## Usage

After configuration, badges will automatically appear for products using the configured attribute terms.

Supported locations:

* Shop pages
* Product category pages
* Single product pages

No shortcode required.

---

## Example

### Attribute Term

| Setting    | Value   |
| ---------- | ------- |
| Name       | Indica  |
| Background | #6B21A8 |
| Text Color | #FFFFFF |
| Icon       | 🌙      |

### Output

```text
🌙 Indica
```

---

## Frequently Asked Questions

### Does this require WooCommerce?

Yes. WooCommerce must be installed and activated.

### Does it support all product attributes?

Yes. The plugin works with WooCommerce global product attributes.

### Can I use emojis as icons?

Yes. Emoji icons are fully supported.

### Does it work with variable products?

Yes. Product attributes assigned to variable products are supported.

### Does it affect performance?

No. The plugin is lightweight and only loads badge data when needed.

---

## Screenshots

### Badge Settings

Configure global badge styling options.

### Attribute Term Settings

Assign colors and icons to attribute terms.

### Shop Page

Display badges on product listings.

### Single Product Page

Display badges on individual product pages.

---

## Changelog

### 1.0.0

* Initial release
* WooCommerce attribute badge support
* Custom badge colors
* Icon support
* Global badge settings
* Shop page integration
* Product page integration

---

## Support

For support, bug reports, and feature requests:

* Website: https://codenitive.com
* GitHub Issues: Use the repository issue tracker

---

## License

GPL-2.0-or-later

This plugin is licensed under the GNU General Public License v2.0 or later.

---

## Author

**Gurjit Singh**

Founder of Codenitive

https://codenitive.com
