<?php

namespace VCR\Filter;

class CurlFilter extends AbstractFilter
{
    const NAME = 'vcr_curl';

    private static $patterns = array(
        '/(?<!::|->)curl_init\s*\(/i'                => '\VCR\LibraryHooks\CurlHook::curl_init(',
        '/(?<!::|->)curl_exec\s*\(/i'                => '\VCR\LibraryHooks\CurlHook::curl_exec(',
        '/(?<!::|->)curl_getinfo\s*\(/i'             => '\VCR\LibraryHooks\CurlHook::curl_getinfo(',
        '/(?<!::|->)curl_setopt\s*\(/i'              => '\VCR\LibraryHooks\CurlHook::curl_setopt(',
        '/(?<!::|->)curl_setopt_array\s*\(/i'        => '\VCR\LibraryHooks\CurlHook::curl_setopt_array(',
        '/(?<!::|->)curl_multi_add_handle\s*\(/i'    => '\VCR\LibraryHooks\CurlHook::curl_multi_add_handle(',
        '/(?<!::|->)curl_multi_remove_handle\s*\(/i' => '\VCR\LibraryHooks\CurlHook::curl_multi_remove_handle(',
        '/(?<!::|->)curl_multi_exec\s*\(/i'          => '\VCR\LibraryHooks\CurlHook::curl_multi_exec(',
        '/(?<!::|->)curl_multi_info_read\s*\(/i'     => '\VCR\LibraryHooks\CurlHook::curl_multi_info_read('
    );

    /**
     * @param string $code
     *
     * @return string
     */
    protected function transformCode($code)
    {
        return preg_replace(array_keys(self::$patterns), array_values(self::$patterns), $code);
    }
}
