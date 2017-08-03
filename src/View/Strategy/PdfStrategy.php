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

namespace DOMPDFModule\View\Strategy;

use Zend\View\ViewEvent;
use DOMPDFModule\View\Model;
use DOMPDFModule\View\Renderer\PdfRenderer;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;

class PdfStrategy extends AbstractListenerAggregate
{
    /**
     * @var PdfRenderer
     */
    protected $renderer;

    /**
     * Constructor
     *
     * @param  PdfRenderer $renderer
     */
    public function __construct(PdfRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, array($this, 'selectRenderer'), $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'injectResponse'), $priority);
    }

    /**
     * Detect if we should use the PdfRenderer based on model type
     *
     * @param  ViewEvent $event
     * @return null|PdfRenderer
     */
    public function selectRenderer(ViewEvent $event)
    {
        $model = $event->getModel();
        
        if ($model instanceof Model\PdfModel) {
            return $this->renderer;
        }

        return null;
    }

    /**
     * Inject the response with the PDF payload and appropriate Content-Type header
     *
     * @param ViewEvent $event
     * @return void
     * @internal param ViewEvent $e
     */
    public function injectResponse(ViewEvent $event)
    {
        $renderer = $event->getRenderer();
        if ($renderer !== $this->renderer) {
            // Discovered renderer is not ours; do nothing
            return;
        }

        $result = $event->getResult();

        if (!is_string($result)) {
            // @todo Potentially throw an exception here since we should *always* get back a result.
            return;
        }
        
        $response = $event->getResponse();
        $response->setContent($result);
        $response->getHeaders()->addHeaderLine('content-type', 'application/pdf');
        
        $fileName = $event->getModel()->getOption('filename');
        if (isset($fileName)) {
            if (substr($fileName, -4) != '.pdf') {
                $fileName .= '.pdf';
            }
            
            $response->getHeaders()->addHeaderLine(
                'Content-Disposition',
                'attachment; filename=' . $fileName
            );
        }
    }
}
