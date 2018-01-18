# Polygon Field Type Bundle for eZ Platform

The Polygon Field Type Bundle for eZ Platform was made to simplify adding and managing polygons (in the form of geojson) to mapbox maps. For a live example, see http://arealguiden.no/eng/Stavanger-region/Forus.

## Installation

### Use Composer

Run the following from your website root folder to install Polygon Field Type Bundle:

```
$ composer require donfelice/PolygonFieldTypeBundle
```

### Activate the bundle

Activate the bundle in app/AppKernel.php file by adding it to the $bundles array in registerBundles method, together with other required bundles:

```javascript
public function registerBundles()
{
    ...
    $bundles[] = new Donfelice\PolygonFieldTypeBundle\DonfelicePolygonFieldTypeBundle();

    return $bundles;
}
```

### Assetic configuration

You need to add it to Assetic configuration in app/config/config.yml, together with EzPlatformAdminUiBundle and all other bundles already configured there:

```
assetic:
    bundles: [EzPlatformAdminUiBundle, DonfelicePolygonFieldTypeBundle]
```
