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
 * @author Raymond J. Kolbe <raymond.kolbe@maine.edu>
 * @copyright Copyright (c) 2012 University of Maine
 * @license	http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace DOMPDFModule;

use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;

class Module
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
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $eventManager = $moduleManager->getEventManager();
        $eventManager->attach('loadModules.post', array($this, 'loadConfiguration'));
    }

    /**
     * @param ModuleEvent $e 
     */
    public function loadConfiguration(ModuleEvent $e)
    {
        define("DOMPDF_LIB_DIR", __DIR__ . '/../../../dompdf/lib/vendor');

        $mergedConfig = $e->getConfigListener()->getMergedConfig();
        $config = $mergedConfig['dompdf_module'];

        foreach ($config as $key => $value) {
            if (! array_key_exists($key, self::$configCompatMapping)) {
                continue;
            }

            define(self::$configCompatMapping[$key], $value);
        }

        require_once __DIR__ . "/../../../dompdf/lib/DOMPDF/functions.inc.php";
        require_once __DIR__ . '/../../config/module.compat.php';
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}
