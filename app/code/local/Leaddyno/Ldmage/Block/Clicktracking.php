<?php

class Leaddyno_Ldmage_Block_Clicktracking extends Mage_Core_Block_Text {

    protected function _toHtml() {

        $config = Mage::getSingleton('leaddyno_ldmage/config');

        

        // If account doesn't have keys, log and insert HTML comment to page. 

        if (!$config->isConfigured()) {

            Mage::log('LeadDyno: The module is not configured, please add public_key and private_key');

            return '<!-- No LeadDyno Account Information Provided -->';

        }



		$ld_domain_field = $config->getLdDomain();

		$ld_watch_field = $config->getLdWatch();

        $public_key = $config->getPublicKey(); 

        

        if ($ld_watch_field == '') {

			$ld_watch= "LeadDyno.autoWatch();";

		}

		else {

			$ld_watch = "LeadDyno.watch(".$ld_watch_field.");";

		}

		

		if ($ld_domain_field == '') {

			$ld_domain = '';

		}

		else {

			$ld_domain = "LeadDyno.domain = '".$ld_domain_field."';";

        }

        

            $this->addText('

                <script type="text/javascript" src="https://static.leaddyno.com/js"></script>

                <script>

                LeadDyno.key = "'.$public_key.'";

                LeadDyno.recordVisit();

                '.$ld_watch.'

                '.$ld_domain.'

                </script>

            ');

        

        return parent::_toHtml();

    }

}




