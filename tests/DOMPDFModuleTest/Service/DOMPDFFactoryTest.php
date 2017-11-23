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
 * @copyright Copyright (c) 2017 Raymond J. Kolbe
 * @license	http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace DOMPDFModule\Service;

use Dompdf\Dompdf;
use DOMPDFModuleTest\Framework\TestCase;

class DOMPDFFactoryTest extends TestCase
{
    /**
     * System under test.
     *
     * @var DOMPDFFactory
     */
    private $factory;

    public function testItCreatesAValidInstance()
    {
        $dompdf = $this->factory->createService($this->getServiceManager());

        $this->assertInstanceOf('\Dompdf\Dompdf', $dompdf);
        $this->assertNotNullOptions($dompdf);
    }

    public function testItCreatesUniqueInstances()
    {
        $firstInstance = $this->factory->createService($this->getServiceManager());
        $secondInstance = $this->factory->createService($this->getServiceManager());

        $this->assertNotSame($firstInstance, $secondInstance);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new DOMPDFFactory();
    }

    /**
     * Asserts that the given DOMPDF instance contains not null options for has options set.
     *
     * @param Dompdf $dompdf
     */
    private function assertNotNullOptions(Dompdf $dompdf)
    {
        $options = $dompdf->getOptions();

        $this->assertNotNull($options->get('temp_dir'), 'temp_dir');
        $this->assertNotNull($options->get('font_dir'), 'font_dir');
        $this->assertNotNull($options->get('font_cache'), 'font_cache');
        $this->assertNotNull($options->get('chroot'), 'chroot');
        $this->assertNotNull($options->get('log_output_file'), 'log_output_file');
        $this->assertNotNull($options->get('default_media_type'), 'default_media_type');
        $this->assertNotNull($options->get('default_paper_size'), 'default_paper_size');
        $this->assertNotNull($options->get('default_font'), 'default_font');
        $this->assertNotNull($options->get('dpi'), 'dpi');
        $this->assertNotNull($options->get('font_height_ratio'), 'font_height_ratio');
        $this->assertNotNull($options->get('is_php_enabled'), 'is_php_enabled');
        $this->assertNotNull($options->get('is_remote_enabled'), 'is_remote_enabled');
        $this->assertNotNull($options->get('is_javascript_enabled'), 'is_javascript_enabled');
        $this->assertNotNull($options->get('is_html5_parser_enabled'), 'is_html5_parser_enabled');
        $this->assertNotNull($options->get('is_font_subsetting_enabled'), 'is_font_subsetting_enabled');
        $this->assertNotNull($options->get('debug_png'), 'debug_png');
        $this->assertNotNull($options->get('debug_keep_temp'), 'debug_keep_temp');
        $this->assertNotNull($options->get('debug_css'), 'debug_css');
        $this->assertNotNull($options->get('debug_layout'), 'debug_layout');
        $this->assertNotNull($options->get('debug_layout_lines'), 'debug_layout_lines');
        $this->assertNotNull($options->get('debug_layout_blocks'), 'debug_layout_blocks');
        $this->assertNotNull($options->get('debug_layout_inline'), 'debug_layout_inline');
        $this->assertNotNull($options->get('debug_layout_padding_box'), 'debug_layout_padding_box');
        $this->assertNotNull($options->get('pdf_backend'), 'pdf_backend');
        $this->assertNotNull($options->get('pdflib_license'), 'pdflib_license');
    }
}
