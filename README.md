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

From any frontend controller route, add the `?debugxml=1` query string parameter.

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

## Warning!

Please do not deploy this to a production Magento environment, and do not commit this code into a repository that may
make its way to a production environment!

## License

This module is licensed under the [MIT license](https://github.com/Batmage/Batmage_Xmldebug/blob/master/LICENSE.md).
