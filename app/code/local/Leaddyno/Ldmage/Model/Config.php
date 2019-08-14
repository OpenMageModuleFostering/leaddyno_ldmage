<?php

class Leaddyno_Ldmage_Model_Config extends Mage_Core_Model_Config_Base {

    var $public;

    var $private_key;



    public function __construct() {

        $this->public_key = Mage::getStoreConfig('ldmage_config/account/public_key');

        $this->private_key = Mage::getStoreConfig('ldmage_config/account/private_key');

        $this->ld_domain = Mage::getStoreConfig('ldmage_config/account/ld_domain');

        $this->ld_watch = Mage::getStoreConfig('ldmage_config/account/ld_watch');   

    }

	

	// LD Integration isn't fully configured without public key and private key

    public function isConfigured() {

        if ($this->public_key != '' || $this->private_key != '') {

            return true;

        }

        return false;

    }





    

    public function getPublicKey() {

        return Mage::getStoreConfig('ldmage_config/account/public_key');

    }

    

        public function getPrivateKey() {

        return Mage::getStoreConfig('ldmage_config/account/private_key');

    }



    public function getLdDomain() {

        return Mage::getStoreConfig('ldmage_config/account/ld_domain');

    }

    

        public function getLdWatch() {

        return Mage::getStoreConfig('ldmage_config/account/ld_watch'); 

    }

}


