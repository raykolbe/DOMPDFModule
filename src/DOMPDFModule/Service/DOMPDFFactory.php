<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Raymond J. Kolbe <rkolbe@gmail.com>
 * @copyright Copyright (c) 2012 University of Maine, 2016 Raymond J. Kolbe
 * @license	http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace DOMPDFModule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DOMPDF;

class DOMPDFFactory implements FactoryInterface
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
     * Creates an instance of DOMPDF.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return DOMPDF
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        defined('DOMPDF_DIR') || define("DOMPDF_DIR", __DIR__ . '/../../../../../dompdf/dompdf');
        defined('DOMPDF_INC_DIR') || define("DOMPDF_INC_DIR", DOMPDF_DIR . "/include");
        defined('DOMPDF_LIB_DIR') || define("DOMPDF_LIB_DIR", DOMPDF_DIR . "/lib");
        defined('DOMPDF_AUTOLOAD_PREPEND') || define("DOMPDF_AUTOLOAD_PREPEND", false);
        defined('DOMPDF_ADMIN_USERNAME') || define("DOMPDF_ADMIN_USERNAME", false);
        defined('DOMPDF_ADMIN_PASSWORD') || define("DOMPDF_ADMIN_PASSWORD", false);

        $config = $serviceLocator->get('config');
        $this->applyCompatGlobals($config['dompdf_module']);

        require_once DOMPDF_LIB_DIR . '/html5lib/Parser.php';
        require_once DOMPDF_INC_DIR . '/functions.inc.php';
        require_once __DIR__ . '/../../../config/module.compat.php';
        
        return new DOMPDF();
    }

    /**
     * Declares global constants supported by DOMPDF lib.
     *
     * @param array $config
     */
    private function applyCompatGlobals(array $config)
    {
        foreach ($config as $key => $value) {
            if (! array_key_exists($key, static::$configCompatMapping)) {
                continue;
            }

            if (defined(static::$configCompatMapping[$key])) {
                continue;
            }

            define(static::$configCompatMapping[$key], $value);
        }
    }
}
