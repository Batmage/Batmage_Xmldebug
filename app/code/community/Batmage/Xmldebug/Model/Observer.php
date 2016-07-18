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

        Mage::app()
            ->getResponse()
            ->setHeader('Content-Type', 'text/xml')
            ->setBody($xml)
            ->sendResponse();
        exit;
    }
}
