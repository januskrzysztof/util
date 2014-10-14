<?php

namespace Tutto\Bundle\UtilBundle\Logic;

use \Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;

/**
 * Class Translator
 * @package Tutto\Bundle\UtilBundle\Logic
 */
class Translator extends BaseTranslator {
    /**
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param null $locale
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null) {
        if(preg_match('/.*:.*$/D', $id)) {
            $parts = explode(':', $id);
            if(count($parts) > 1) {
                $id = $parts[1];
                $domain = $parts[0];
            }
        }

        return parent::trans($id, $parameters, $domain, $locale);
    }
}