<?php


/**
 * MyGiftArena Voucher Payment Method Model
 * @category MyGiftArena
 * @package MyGiftArena_VoucherPayment
 * @author Nekon James <@NekonJames> <breantech@yahoo.com>
 */
class Asos_Mygiftarena_Model_Payment extends Mage_Payment_Model_Method_Abstract {
    //put your code here
    
    protected $_code = 'mygiftarena';
    protected $_isInitializeNeeded      = true;
    protected $_canUseInternal          = false;
    protected $_canUseForMultishipping  = false;
    
    public function getOrderPlaceRedirectUrl(){    
        //when you click on place order you will be redirected on this url, if you don't want this action remove this method
        return Mage::getUrl('mygiftarena/payment/redirect', array('_secure' => true));
        
    }
}
