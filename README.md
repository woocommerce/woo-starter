# WooCommerce Starter

Welcome to the WooCommerce Starter ClI. This CLI will help you get started with WooCommerce Extension development.

### Requirements
- PHP 7.4+
- [Docker](https://www.docker.com/)
- [Composer](https://getcomposer.org/)
- [Node](https://nodejs.org/en)
- [Box](https://packagist.org/packages/humbug/box)

### Development
You have 3 ways of developing this CLI tool.
1. Run `php src/woo-starter.php` to run the CLI tool.
2. Manually build `woo-starter.phar` using `box build` and run `php woo-starter.phar` to run the CLI tool.
3. Run `php watch.php` to watch for changes and automatically build `woo-starter.phar` and run the CLI tool.


### Usage
The CLI only exposes a single `create` command. To use it, run `php src/woo-starter.php create` and follow the onscreen prompts.

### About the Generated Plugin
The generated plugin is a modified version of the [WooCommerce Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/tree/master) with a few additions to help get folks up with developing their WooCommerce Extension.

The additions are as follows:
- The plugin will block activation if WooCommerce is not installed and activated.
- The plugin project uses composer and npm to manage dependencies and provides the user with useful tooling to aid their development workflow.

The following scripts are available to the developer after the plugin as been successfully created:
- `npm run start` - Starts a `wp-env` environment with WooCommerce and the newly generated plugin installed.
- `npm run phpcs` - Runs `phpcs` on the plugin.
- `npm run phpcbf` - Runs `phpcbf` on the plugin.
- `npm run security` - Runs `phpcs` with the [QIT Security ruleset](https://woocommerce.github.io/qit-documentation/#/test-types/security?id=which-phpcs-rules-are-enabled) on the plugin.
- `npm run test:unit` - Runs `phpunit` on the plugin.