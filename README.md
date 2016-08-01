# Batmage_Xmldebug

A simple module for Magento 1.x to help you with debugging XML layouts.

Brought to you by Batmage!

## Installation

The easiest way is using [modman](https://github.com/colinmollenhour/modman), if you can:

```bash
modman clone https://github.com/Batmage/Batmage_Xmldebug.git
```

Clear your cache before use.

## Usage

### Layout XML

From any frontend controller route, add the `?debugxml=layout` query string parameter.

This will create a XML dump of some useful route arguments and your XML layout and will output it as XML to the browser
so that your browser can format it nicely for you, example:

```xml
<?xml version="1.0"?>
    <xml_layout_debugging>
        <!-- Useful information about how the controller is reacting -->
        <route>
            <name>catalog</name>
            <requested_route_name>catalog</requested_route_name>
            <requested_controller_name>product</requested_controller_name>
            <requested_action_name>view</requested_action_name>
        </route>
        <!-- The handles that are active for this route -->
        <handles>
            <handle>default</handle>
            <handle>STORE_us_eng</handle>
            <handle>THEME_frontend_base_default</handle>
            <handle>catalog_product_view</handle>
            <handle>PRODUCT_TYPE_configurable</handle>
            <handle>PRODUCT_123</handle>
            <handle>customer_logged_out</handle>
            <handle>MAP_price_msrp_item</handle>
        </handles>
        <!-- The layout XML output -->
        <output>
            <block name="formkey" type="core/template" template="core/formkey.phtml"/>
            <label>All Pages</label>
            <block type="page/html" name="root" output="toHtml" template="page/3columns.phtml">
                <block type="page/html_head" name="head" as="head">
                    <action method="addJs">
                        <script>prototype/prototype.js</script>
                    </action>
                    <action method="addJs">
                        <script>lib/jquery/jquery-1.10.2.min.js</script>
                    </action>
...
```

### Helper declarations

You can add the `?debugxml=helpers` query string param to a frontend route in Magento
to dump out the *compiled* configuration for helpers, including any rewrites that are defined, e.g.:

```xml
<xml_helper_debugging>
    <core>
        <rewritten_by>
            <data>Myvendor_Mypackage_Helper_Data</data>
            <js>Myvendor_Mypackage_Helper_Corejsoverride</js>
        </rewritten_by>
    </core>
    <admin>
        <code_pool>core</code_pool>
        <class>Mage_Admin_Helper</class>
        <rewritten_by>
            <data>Myvendor_Mypackage_Helper_Data</data>
        </rewritten_by>
    </admin>
    <!-- ... etc -->
</xml_helper_debugging>
```

#### Filtering

You can provide a text filter string via the `debug_filter` query string parameter to perform a simple
kind of search on the results, for example `?debugxml=helpers&debug_filter=inventory`:

```xml
<xml_helper_debugging>
    <cataloginventory>
        <code_pool>core</code_pool>
        <class>Mage_CatalogInventory_Helper</class>
    </cataloginventory>
    <!-- ... etc -->
</xml_helper_debugging>
```

## Warning!

Please do not deploy this to a production Magento environment, and do not commit this code into a repository that may
make its way to a production environment!

## License

This module is licensed under the [MIT license](https://github.com/Batmage/Batmage_Xmldebug/blob/master/LICENSE.md).
