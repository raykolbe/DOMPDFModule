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

use DOMPDFModule\View\Model;
use DOMPDFModule\View\Renderer\PdfRenderer;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\View\ViewEvent;

class PdfStrategy implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var PdfRenderer
     */
    protected $renderer;

    /**
     * Constructor
     *
     * @param  PdfRenderer $renderer
     * @return void
     */
    public function __construct(PdfRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Attach the aggregate to the specified event manager
     *
     * @param  EventManagerInterface $events
     * @param  int $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, array($this, 'selectRenderer'), $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'injectResponse'), $priority);
    }

    /**
     * Detach aggregate listeners from the specified event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
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
     * @param  ViewEvent $e
     * @return void
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
