<?php
class Leaddyno_Ldmage_Model_Observer {
    public function orderModified($observer) {
        $event = $observer->getEvent();
        $order = $event->getOrder();

        $config = Mage::getSingleton('leaddyno_ldmage/config');
        $privatekey = $config->getPrivateKey();

	    try {
            Mage::log("LD Transaction status changed to ".$order->getStatus()." -> ".$order->getCouponCode());

			// Only refund when a full refund is provided. 
            if ($order->getStatus() == 'closed') {
    
                $httpClient = new Zend_Http_Client("https://api.leaddyno.com/v1/purchases");
                $httpClient->setParameterPost([
                        'key' => $privatekey,
                        'purchase_code' => $order->getIncrementID(),
                        ]);
                $httpClient->request('DELETE');
                $param = $httpClient->getUri()->getQuery();
    
            }


			// Processing indicates that the invoice has either been invoiced OR shipped. It is not a 'pending' purchase. 
            if ($order->getStatus() == 'processing') {
                if ($order->getBaseTotalPaid() > 0) {

                    /*	TODO :: Per Product Integration (REST API requires JSON object, which is a pain)	
                     * 
                     * $items = $order->getAllVisibleItems();
                     $line_items = [];
                     foreach($items as $i=>$item) {
                     $productid = $item->getProductId();
                     $product = Mage::getModel('catalog/product')->load($productid);
                     $line_items[$i] = [ 
                     "sku" =>$product->getSku(),
                     "quantity" => "1",
                     "description" => $product->getName(),
                     "amount" => $product->getPrice(),
                     ];
                     }	   
                     Mage::log($line_items.$items);
                     */
                     
                    
                    // Send POST to the purchases endpoint with private key
                    $httpClient = new Varien_Http_Client();
                    $httpClient->setUri('https://api.leaddyno.com/v1/purchases');
                    $httpClient->setMethod(Zend_Http_Client::POST);
                    $httpClient->setParameterPost([
                            'key' => $privatekey,
                            'email' => $order->getCustomerEmail(),
                            'purchase_code' => $order->getIncrementId(),
                            'purchase_amount' => $order->getBaseSubtotal(),
                            'code' => $order->getCouponCode(),
                            ]);
                    $httpResp = $httpClient->request();
                    $param = $httpClient->getUri()->getQuery();
                    Mage::log($param);
                    Mage::log("HTTP Response: ".$httpResp->getBody().", but purchase total was less than zero.");
                    Mage::log("Order status ".$order->getStatus().", but purchase total was less than zero.");

                }
                else { 
                    Mage::log("Order status ".$order->getStatus().", but purchase total was less than zero.");
                }
                
                return $this;
            }

        }
    
        catch (Exception $e) {
            Mage::log("LD Transaction status changed to ".$order->getStatus());
        }

        return $this;
    }
}
