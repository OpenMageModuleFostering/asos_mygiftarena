<?php
/*
Mygateway Payment Controller
By: Junaid Bhura
www.junaidbhura.com
*/

class Asos_Mygiftarena_PaymentController extends Mage_Core_Controller_Front_Action {
    
    // The redirect action is triggered when someone places an order
	public function redirectAction() {
            $this->loadLayout();
            $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','mygiftarena',array('template' => 'mygiftarena/redirect.phtml'));
            $this->getLayout()->getBlock('content')->append($block);
            $this->renderLayout();
	}
	
        public function failureAction(){
            $this->cancelAction();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure'=>true));
        }

                // The response action is triggered when your gateway sends back a response after processing the customer's payment
	public function successAction() {
//		if($this->getRequest()->isPost()) {
			
			
            $validated = false;
            $orderId = FALSE; 
            $code = FALSE; 

            if($this->getRequest()->getPost('responseCode') && $this->getRequest()->getPost('orderId')){
                $code = $this->getRequest()->getPost('responseCode');
                $orderId = $this->getRequest()->getPost('orderId');
            }  

            if($code === 200 || $code == "200"){                
                $validated = TRUE;
            }

            if($validated) {
                
                    // Payment was successful, so update the order's state, send order email and move to the success page
                    $order = Mage::getModel('sales/order');                    
                    $order->loadByIncrementId($orderId);
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has authorized the payment.');

                    $order->sendNewOrderEmail();
                    $order->setEmailSent(true);

                    $order->save();

                    Mage::getSingleton('checkout/session')->unsQuoteId();

                    Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=>true));
            }
            else {
                    // There is a problem in the response we got
                    $this->cancelAction();
                    Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure'=>true));
            }
                        
	}
	
	// The cancel action is triggered when an order is to be cancelled
	public function cancelAction() {
            if (Mage::getSingleton('checkout/session')->getLastRealOrderId()) {
                $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
                if($order->getId()) {
                                    // Flag the order as 'cancelled' and save it
                                    $order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.')->save();
                            }
            }
	}
}