# Novapost
Elevate your e-commerce shipping experience with the Nova Post shipping plugin. This versatile tool integrates seamlessly with your store, enabling you to create and manage shipments effortlessly. Whether shipping to home addresses or Nova Post divisions, this plugin ensures a smooth delivery process.

## Key Features
- **Shipments**: efficiently generate shipments directly from your store's software, reducing manual entry and saving time.
- **Home address and division delivery**: offer flexible delivery options for your customers, including home address delivery and division pick-up.
- **Print Labels**: simplify your packaging process with easy label printing in various formats.
- **Pick-up settings**: create a courier call request automatically using the plugin functionality.
- **Custom Pricing**: use our special pricing model offerings  for you calculated according to your needs.
- **Support for Multiple Destinations**: ship your orders from your location to various countries, including the EU and the UK.
- **Delivery Options**: use different delivery methods to suit your customers' needs, including delivery to the post office, address, or the nearest postal machine.

## Installation

### Using composer
Use the package manager composer

```bash
composer require nova-poshta-global/np-shipping-magento
```

### Manual install

1. Unpack into the `app/code/Novapost/Shipping` folder.

2. Enable module
```bash
php bin/magento module:enable Novapost_Shipping
```

3. Run Setup Upgrade:
```bash
php bin/magento setup:upgrade
```
4. Compile Dependency Injection:
```bash
php bin/magento setup:di:compile
```

5. Deploy Static Content:
```bash
php bin/magento setup:static-content:deploy
```

6. Clear Cache:
```bash
php bin/magento cache:flush
```

## API KEY
Email servicedesk@novapost.com to register a user for the API.
During the registration process, you will receive an API access key. Make sure to store this information in a secure place.

## Settings

Admin Panel:

1. Log in to the Magento admin panel.
   Stores -> Configuration -> Sales -> Delivery Methods. Select NOVA POST in tab and find the 'API Key (apiKey)' and enter the provided key into this field. Save the settings.
2. Navigate to Stores -> Configuration -> NOVA POST -> Shipping synchronize.
   Click the "Synchronize Now" button to manually initiate the data synchronization process.

3. After the synchronization is complete, you need to enter your personal information.
   Navigate to Stores -> Configuration -> Sales -> Delivery Methods. Select the NOVA POST tab and continue configuring the module.

## License

[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)