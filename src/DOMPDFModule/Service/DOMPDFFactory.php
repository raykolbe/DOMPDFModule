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

use Dompdf\Dompdf;
use Dompdf\Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DOMPDFFactory implements FactoryInterface
{
    /**
     * Creates an instance of Dompdf.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Dompdf
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $moduleConfig = $serviceLocator->get('config')['dompdf_module'];

        $options = [
            'temp_dir'                   => $moduleConfig['temporary_directory'],
            'font_dir'                   => $moduleConfig['font_directory'],
            'font_cache'                 => $moduleConfig['font_cache_directory'],
            'chroot'                     => $moduleConfig['chroot'],
            'log_output_file'            => $moduleConfig['log_output_file'],
            'default_media_type'         => $moduleConfig['default_media_type'],
            'default_paper_size'         => $moduleConfig['default_paper_size'],
            'default_font'               => $moduleConfig['default_font'],
            'dpi'                        => $moduleConfig['dpi'],
            'font_height_ratio'          => $moduleConfig['font_height_ratio'],
            'is_php_enabled'             => $moduleConfig['enable_php'],
            'is_remote_enabled'          => $moduleConfig['enable_remote'],
            'is_javascript_enabled'      => $moduleConfig['enable_javascript'],
            'is_html5_parser_enabled'    => $moduleConfig['enable_html5parser'],
            'is_font_subsetting_enabled' => $moduleConfig['enable_fontsubsetting'],
            'debug_png'                  => $moduleConfig['debug_png'],
            'debug_keep_temp'            => $moduleConfig['debug_keep_temp'],
            'debug_css'                  => $moduleConfig['debug_css'],
            'debug_layout'               => $moduleConfig['debug_layout'],
            'debug_layout_lines'         => $moduleConfig['debug_layout_lines'],
            'debug_layout_blocks'        => $moduleConfig['debug_layout_blocks'],
            'debug_layout_inline'        => $moduleConfig['debug_layout_inline'],
            'debug_layout_padding_box'   => $moduleConfig['debug_layout_padding_box'],
            'pdf_backend'                => $moduleConfig['pdf_backend'],
            'pdflib_license'             => $moduleConfig['pdflib_license']
        ];

        return new Dompdf(new Options($options));
    }
}
