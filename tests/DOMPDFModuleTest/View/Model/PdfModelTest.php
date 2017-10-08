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

namespace DOMPDFModuleTest\View\Model;

use DOMPDFModule\View\Model\PdfModel;

class PdfModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var PdfModel
     */
    private $model;

    public function testItHasDefaultPaperSize()
    {
        $this->assertEquals('8x11', $this->model->getOption('paperSize'));
    }

    public function testItHasDefaultPaperOrientation()
    {
        $this->assertEquals('portrait', $this->model->getOption('paperOrientation'));
    }

    public function testItHasDefaultBasePath()
    {
        $this->assertEquals('/', $this->model->getOption('basePath'));
    }

    public function testItHasDefaultFileName()
    {
        $this->assertEquals('untitled.pdf', $this->model->getOption('fileName'));
    }

    public function testItHasDefaultDisplayOption()
    {
        $this->assertEquals(PdfModel::DISPLAY_INLINE, $this->model->getOption('display'));
    }

    public function testItIsTerminal()
    {
        $this->assertTrue($this->model->terminate());
    }

    public function testItDoesNotHaveCaptureToVariable()
    {
        $this->assertNull($this->model->captureTo());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->model = new PdfModel();
    }
}
