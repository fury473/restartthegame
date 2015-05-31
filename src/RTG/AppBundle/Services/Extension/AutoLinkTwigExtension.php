<?php

namespace RTG\AppBundle\Services\Extension;

use Twig_Extension;
use Twig_Filter_Method;

class AutoLinkTwigExtension extends Twig_Extension
{

    public function getName()
    {
        return 'auto_link_twig_extension';
    }

    public function getFilters()
    {
        return array(
            'auto_link_text' => new \Twig_Filter_Method(
                    $this, 'autoConvertUrls', array(
                'pre_escape' => 'html',
                'is_safe' => array('html'),
                    )
            )
        );
    }

    /**
     * method that finds different occurrences of urls or email addresses in a string
     * @param string $string input string
     * @return string with replaced links
     */
    public function autoConvertUrls($string)
    {
        $pattern = '/(href=")?([-a-zA-Z0-9@:%_\+.~#?&\/\/=]{2,256}\.[a-z]{2,4}\b(\/?[-\p{L}0-9@:%_\+.~#?&;\/\/=\(\)]*)?)/u';
        $stringFiltered = preg_replace_callback($pattern, array($this, 'callbackReplace'), $string);
        return $stringFiltered;
    }

    public function callbackReplace($matches)
    {
        if ($matches[1] !== '') {
            return $matches[0]; // don't modify existing <a href="">links</a>
        }
        $url = $matches[2];
        $urlWithPrefix = $matches[2];
        if (strpos($url, 'https://') === 0) {
            $urlWithPrefix = $url;
        } elseif (strpos($url, 'http://') !== 0) {
            $urlWithPrefix = 'http://' . $url;
        }
        // ignore tailing special characters
        // TODO: likely this could be skipped entirely with some more tweakes to the regular expression
        if (preg_match("/^(.*)(\.|\,|\?)$/", $urlWithPrefix, $matches)) {
            $urlWithPrefix = $matches[1];
            $url = substr($url, 0, -1);
            $punctuation = $matches[2];
        } else {
            $punctuation = '';
        }
        return '<a href="' . $urlWithPrefix . '" target="_blank">' . $url . '</a>' . $punctuation;
    }

}
