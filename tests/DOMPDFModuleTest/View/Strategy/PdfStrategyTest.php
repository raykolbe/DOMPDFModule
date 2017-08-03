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

namespace DOMPDFModuleTest\View\Strategy;

use Zend\View\ViewEvent;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManager;
use Zend\View\Renderer\PhpRenderer;
use DOMPDFModule\View\Model\PdfModel;
use Zend\Http\Response as HttpResponse;
use DOMPDFModuleTest\Framework\TestCase;
use Zend\View\Resolver\TemplatePathStack;
use DOMPDFModule\View\Renderer\PdfRenderer;
use DOMPDFModule\View\Strategy\PdfStrategy;
use Zend\EventManager\Test\EventListenerIntrospectionTrait;

class PdfStrategyTest extends TestCase
{
    use EventListenerIntrospectionTrait;

    /**
     * @var ViewEvent
     */
    private $event;

    /**
     * @var PdfRenderer
     */
    private $renderer;

    /**
     * @var TemplatePathStack
     */
    private $resolver;

    /**
     * @var HttpResponse
     */
    private $response;

    /**
     * System under test.
     *
     * @var PdfStrategy
     */
    private $strategy;

    public function testEventSubscribers()
    {
        //As suggested in the documentation, we no longer have the need to verify whether or not the event is attached
        //http://zendframework.github.io/zend-eventmanager/migration/removed/#eventmanagerinterfacegetevents-and-getlisteners

        $manager = new EventManager();
        $this->strategy->attach($manager);

        $events = $this->getEventsFromEventManager($manager);

        $this->assertCount(2, $events, 'Renderer listener after attach');
        $this->assertEquals([ViewEvent::EVENT_RENDERER, ViewEvent::EVENT_RESPONSE], $events);

        $this->strategy->detach($manager);

        $this->assertCount(0, $this->getEventsFromEventManager($manager), 'Renderer listener after detach');
    }

    public function testSelectsRendererWhenProvidedPdfModel()
    {
        $this->event->setModel(new PdfModel());
        $result = $this->strategy->selectRenderer($this->event);
        $this->assertSame($this->renderer, $result);
    }

    public function testDoesNotSelectRendererWhenNotProvidedPdfModel()
    {
        $this->event->setModel(new ViewModel());
        $result = $this->strategy->selectRenderer($this->event);
        $this->assertNull($result);
    }

    public function testDoesNotRenderPdfWhenRenderMismatch()
    {
        $this->event->setRenderer(new PhpRenderer());
        $result = $this->strategy->injectResponse($this->event);
        $this->assertNull($result);
    }

    public function testDoesNotRenderPdfWhenResultIsNotString()
    {
        $this->event->setRenderer($this->renderer);
        $this->event->setResult(new \stdClass());

        $result = $this->strategy->injectResponse($this->event);

        $this->assertNull($result);
    }
    
    public function testContentTypeResponseHeader()
    {
        $model = new PdfModel();
        $model->setTemplate('basic.phtml');
        
        $this->event->setModel($model);
        $this->event->setResponse($this->response);
        $this->event->setRenderer($this->renderer);
        $this->event->setResult($this->renderer->render($model));
        
        $this->strategy->injectResponse($this->event);
        
        $headers           = $this->event->getResponse()->getHeaders();
        $contentTypeHeader = $headers->get('content-type');
        
        $this->assertInstanceOf('Zend\Http\Header\ContentType', $contentTypeHeader);
        $this->assertEquals($contentTypeHeader->getFieldValue(), 'application/pdf');
    }
    
    public function testResponseHeadersWithFileName()
    {
        $model = new PdfModel();
        $model->setTemplate('basic.phtml');
        $model->setOption('filename', 'testPdfFileName');
        
        $this->event->setModel($model);
        $this->event->setResponse($this->response);
        $this->event->setRenderer($this->renderer);
        $this->event->setResult($this->renderer->render($model));
        
        $this->strategy->injectResponse($this->event);
        
        $headers = $this->event->getResponse()->getHeaders();
        $contentDisposition = $headers->get('Content-Disposition');
        
        $this->assertInstanceOf('Zend\Http\Header\ContentDisposition', $contentDisposition);
        $this->assertEquals($contentDisposition->getFieldValue(), 'attachment; filename=testPdfFileName.pdf');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->renderer = new PdfRenderer();
        $this->strategy = new PdfStrategy($this->renderer);
        $this->event    = new ViewEvent();
        $this->response = new HttpResponse();

        $this->resolver = new TemplatePathStack();
        $this->resolver->addPath(dirname(__DIR__) . '/_templates');

        $this->renderer->setResolver($this->resolver);

        $htmlRenderer = new PhpRenderer();
        $htmlRenderer->setResolver($this->resolver);
        $this->renderer->setHtmlRenderer($htmlRenderer);
        $this->renderer->setEngine($this->getServiceManager()->get('DOMPDF'));
    }
}
