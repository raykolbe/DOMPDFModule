<?php

namespace DOMPDFModule\Module;

use Zend\ModuleManager\ModuleEvent;

class ConfigListener
{
    /**
     * An array of keys that map DOMPDF define keys to DOMPDFModule config's
     * keys.
     * 
     * @var array
     */
    private static $configCompatMapping = array(
        'font_directory'            => 'DOMPDF_FONT_DIR',
        'font_cache_directory'      => 'DOMPDF_FONT_CACHE',
        'temporary_directory'       => 'DOMPDF_TEMP_DIR',
        'chroot'                    => 'DOMPDF_CHROOT',
        'unicode_enabled'           => 'DOMPDF_UNICODE_ENABLED',
        'enable_fontsubsetting'     => 'DOMPDF_ENABLE_FONTSUBSETTING',
        'pdf_backend'               => 'DOMPDF_PDF_BACKEND',
        'default_media_type'        => 'DOMPDF_DEFAULT_MEDIA_TYPE',
        'default_paper_size'        => 'DOMPDF_DEFAULT_PAPER_SIZE',
        'default_font'              => 'DOMPDF_DEFAULT_FONT',
        'dpi'                       => 'DOMPDF_DPI',
        'enable_php'                => 'DOMPDF_ENABLE_PHP',
        'enable_javascript'         => 'DOMPDF_ENABLE_JAVASCRIPT',
        'enable_remote'             => 'DOMPDF_ENABLE_REMOTE',
        'log_output_file'           => 'DOMPDF_LOG_OUTPUT_FILE',
        'font_height_ratio'         => 'DOMPDF_FONT_HEIGHT_RATIO',
        'enable_css_float'          => 'DOMPDF_ENABLE_CSS_FLOAT',
        'enable_html5parser'        => 'DOMPDF_ENABLE_HTML5PARSER',
        'debug_png'                 => 'DEBUGPNG',
        'debug_keep_temp'           => 'DEBUGKEEPTEMP',
        'debug_css'                 => 'DEBUGCSS',
        'debug_layout'              => 'DEBUG_LAYOUT',
        'debug_layout_links'        => 'DEBUG_LAYOUT_LINES',
        'debug_layout_blocks'       => 'DEBUG_LAYOUT_BLOCKS',
        'debug_layout_inline'       => 'DEBUG_LAYOUT_INLINE',
        'debug_layout_padding_box'  => 'DEBUG_LAYOUT_PADDINGBOX'
    );
    
    /**
     * Event callback called after modules are loaded, responsible
     * for loading DOMPDF config.
     * 
     * @param ModuleEvent $e
     * @return void 
     */
    public function loadConfig(ModuleEvent $event)
    {
        define("DOMPDF_DIR", __DIR__ . '/../../../vendor/dompdf');
        define("DOMPDF_INC_DIR", DOMPDF_DIR . "/include");
        define("DOMPDF_LIB_DIR", DOMPDF_DIR . "/lib");

        $config = $event->getConfigListener()->getMergedConfig();
        foreach ($config['dompdf_module'] as $key => $value) {
            if (! array_key_exists($key, self::$configCompatMapping)) {
                continue;
            }

            define(self::$configCompatMapping[$key], $value);
        }

        define("DOMPDF_AUTOLOAD_PREPEND", false);
        
        require_once DOMPDF_INC_DIR . '/functions.inc.php';
        require_once DOMPDF_LIB_DIR . '/html5lib/Parser.php';
        require_once DOMPDF_INC_DIR . '/autoload.inc.php';
        require_once __DIR__ . '/../../../config/module.compat.php';
    }
}