<?php
/**
 * Provide ability to dump layout XML to the browser
 *
 * @category Mage
 * @package  Batmage_Xmldebug
 * @author   Robbie Averill <robbie@averill.co.nz>
 */
class Batmage_Xmldebug_Model_Observer
{
    /**
     * If a debug query string parameter is given , format and dump the layout XML to the browser
     *
     * @param  Varien_Event_Observer $observer
     * @return self|void
     */
    public function debugXml(Varien_Event_Observer $observer)
    {
        $request  = Mage::app()->getRequest();
        if (!$request->getParam('debugxml')) {
            return $this;
        }

        // Handle multiple debug types
        if (method_exists($this, $request->getParam('debugxml'))) {
            return $this->{$request->getParam('debugxml')}();
        }

        // @todo Rewrite using SimpleXML objects
        $xml = '<?xml version="1.0"?>
        <xml_layout_debugging>
            <!-- Useful information about how the controller is reacting -->
            <route>
                <name>' . $request->getRouteName() . '</name>
                <requested_route_name>' . $request->getRequestedRouteName() . '</requested_route_name>
                <requested_controller_name>' . $request->getRequestedControllerName() . '</requested_controller_name>
                <requested_action_name>' . $request->getRequestedActionName() . '</requested_action_name>
            </route>
            <!-- The handles that are active for this route -->
            <handles>';

        foreach ($observer->getLayout()->getUpdate()->getHandles() as $handle) {
            $xml .= '<handle>' . $handle . '</handle>';
        }

        $xml .= '
            </handles>
            <!-- The layout XML output -->
            <output>' . $observer->getLayout()->getUpdate()->asString() . '</output>
        </xml_layout_debugging>';

        $this->_output($xml);
    }

    /**
     * Gets debugging information about the declared helpers, including their rewrites
     */
    public function helpers()
    {
        /** @var Varien_Simplexml_Element $output */
        $wrapper = new SimpleXMLElement('<xml_helper_debugging/>');

        /** @var Mage_Core_Model_Config $config */
        $config = Mage::app()->getConfig();

        /** @var Mage_Core_Model_Config_Element $helperDefinitions */
        $helperDefinitions = $config->getNode()->xpath('//global//helpers');
        $helperDefinitions = array_pop($helperDefinitions);

        foreach ($helperDefinitions as $namespace => $helperConfig) { /** @var Varien_Simplexml_Element $helperConfig */
            $helper = $wrapper->addChild($namespace);

            // Get the module name by removing the _helper extension
            $moduleClass = substr($helperConfig->class, 0, strrpos($helperConfig->class, '_Helper'));
            $moduleName = $config->getModuleConfig($moduleClass);
            if ($codePool = (string) $moduleName->codePool) {
                $helper->addChild('code_pool', $moduleName->codePool);
            }

            foreach ($helperConfig as $key => $value) {
                $this->_recursiveAddChild($helper, $key, $value);
            }
        }

        $this->_filter($wrapper);

        $this->_output($wrapper->asXML());
    }

    /**
     * Recursively ad the contents of a config element to a SimpleXMLElement output
     * @param  SimpleXMLElement                      $element
     * @param  string                                $key
     * @param  string|Mage_Core_Model_Config_Element $value
     * @return self
     */
    protected function _recursiveAddChild(SimpleXMLElement $element, $key, $value)
    {
        if ('' !== (string) $value) {
            $element->addChild($key, $value);
            return $this;
        }

        if ('rewrite' === $key) {
            // A more friendly name
            $key = 'rewritten_by';
        }

        $element = $element->addChild($key);
        foreach ($value as $k => $v) {
            $this->_recursiveAddChild($element, $k, $v);
        }

        return $this;
    }

    /**
     * Output XML content to the browser
     * @param  string $content
     * @return void
     */
    protected function _output($content)
    {
        Mage::app()
            ->getResponse()
            ->setHeader('Content-Type', 'text/xml')
            ->setBody($content)
            ->sendResponse();
        exit;
    }

    /**
     * Filters the output by a query string parameter for searching
     * @param  SimpleXMLElement $content
     * @return self
     */
    protected function _filter(SimpleXMLElement $content)
    {
        $filterParam = Mage::app()->getRequest()->getParam('debug_filter');
        if (empty($filterParam)) {
            return $this;
        }

        foreach ($content->children() as $namespace => $child) {
            if (false === stripos($namespace, $filterParam)) {
                $remove[] = (string) $namespace;
            }
        }

        foreach ($remove as $namespace) {
            unset($content->{$namespace});
        }
    }
}
