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

namespace DOMPDFModuleTest\View\Strategy;

use Zend\View\Model\JsonModel;
use DOMPDFModuleTest\Framework\TestCase;
use DOMPDFModule\View\Model\PdfModel;
use DOMPDFModule\View\Renderer\PdfRenderer;

class PdfRendererTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $htmlRenderer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $engine;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $resolver;

    /**
     * System under test.
     *
     * @var PdfRenderer
     */
    private $renderer;

    public function testItHasAnHtmlRenderer()
    {
        $this->assertInstanceOf('Zend\View\Renderer\RendererInterface', $this->renderer->getHtmlRenderer());
    }

    public function testItHasAnEngine()
    {
        $this->assertInstanceOf('\Dompdf\Dompdf', $this->renderer->getEngine());
    }

    public function testItRendersAPdfModel()
    {
        $this->htmlRenderer->expects($this->once())->method('render');

        $this->engine->expects($this->once())->method('setPaper');
        $this->engine->expects($this->once())->method('setBasePath');
        $this->engine->expects($this->once())->method('loadHtml');
        $this->engine->expects($this->once())->method('render');
        $this->engine->expects($this->once())->method('output');

        $this->renderer->render(new PdfModel());
    }

    /**
     * @expectedException \Zend\View\Exception\InvalidArgumentException
     */
    public function testItDoesNotRenderOtherModels()
    {
        $this->htmlRenderer->expects($this->never())->method('render');

        $this->engine->expects($this->never())->method('render');
        $this->engine->expects($this->never())->method('output');

        $this->renderer->render(new JsonModel());
    }

    /**
     * @expectedException \Zend\View\Exception\InvalidArgumentException
     */
    public function testItDoesNotRenderNamedModels()
    {
        $this->htmlRenderer->expects($this->never())->method('render');

        $this->engine->expects($this->never())->method('render');
        $this->engine->expects($this->never())->method('output');

        $this->renderer->render('named-model');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->htmlRenderer = $this->getMock('Zend\View\Renderer\RendererInterface');
        $this->resolver = $this->getMock('Zend\View\Resolver\ResolverInterface');
        $this->engine = $this->getMockBuilder('\Dompdf\Dompdf')->disableOriginalConstructor()->getMock();

        $this->renderer = new PdfRenderer();
        $this->renderer->setHtmlRenderer($this->htmlRenderer);
        $this->renderer->setResolver($this->resolver);
        $this->renderer->setEngine($this->engine);
    }
}
