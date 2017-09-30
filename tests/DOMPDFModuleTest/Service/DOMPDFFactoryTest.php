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

use \DOMPDF;
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

        $this->assertInstanceOf('\DOMPDF', $dompdf);
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
        $this->assertNotNull($dompdf->get_option('temp_dir'), 'temp_dir');
        $this->assertNotNull($dompdf->get_option('font_dir'), 'font_dir');
        $this->assertNotNull($dompdf->get_option('font_cache'), 'font_cache');
        $this->assertNotNull($dompdf->get_option('chroot'), 'chroot');
        $this->assertNotNull($dompdf->get_option('log_output_file'), 'log_output_file');
        $this->assertNotNull($dompdf->get_option('default_media_type'), 'default_media_type');
        $this->assertNotNull($dompdf->get_option('default_paper_size'), 'default_paper_size');
        $this->assertNotNull($dompdf->get_option('default_font'), 'default_font');
        $this->assertNotNull($dompdf->get_option('dpi'), 'dpi');
        $this->assertNotNull($dompdf->get_option('font_height_ratio'), 'font_height_ratio');
        $this->assertNotNull($dompdf->get_option('enable_php'), 'enable_php');
        $this->assertNotNull($dompdf->get_option('enable_remote'), 'enable_remote');
        $this->assertNotNull($dompdf->get_option('enable_javascript'), 'enable_javascript');
        $this->assertNotNull($dompdf->get_option('enable_html5_parser'), 'enable_html5_parser');
        $this->assertNotNull($dompdf->get_option('enable_font_subsetting'), 'enable_font_subsetting');
        $this->assertNotNull($dompdf->get_option('debug_png'), 'debug_png');
        $this->assertNotNull($dompdf->get_option('debug_keep_temp'), 'debug_keep_temp');
        $this->assertNotNull($dompdf->get_option('debug_css'), 'debug_css');
        $this->assertNotNull($dompdf->get_option('debug_layout'), 'debug_layout');
        $this->assertNotNull($dompdf->get_option('debug_layout_lines'), 'debug_layout_lines');
        $this->assertNotNull($dompdf->get_option('debug_layout_blocks'), 'debug_layout_blocks');
        $this->assertNotNull($dompdf->get_option('debug_layout_inline'), 'debug_layout_inline');
        $this->assertNotNull($dompdf->get_option('debug_layout_padding_box'), 'debug_layout_padding_box');
    }
}
