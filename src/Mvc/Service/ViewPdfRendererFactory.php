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

namespace DOMPDFModule\Mvc\Service;

use Interop\Container\ContainerInterface;
use DOMPDFModule\View\Renderer\PdfRenderer;
use Zend\ServiceManager\Factory\FactoryInterface;

class ViewPdfRendererFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * @var $resolver \Zend\View\Resolver\AggregateResolver
         * @var $renderer \Zend\View\Renderer\PhpRenderer
         */
        $resolver = $container->get('ViewResolver');
        $renderer = $container->get('ViewRenderer');

        $pdfRenderer = new PdfRenderer();
        $pdfRenderer->setResolver($resolver);
        $pdfRenderer->setHtmlRenderer($renderer);
        $pdfRenderer->setEngine($container->get('DOMPDF'));

        return $pdfRenderer;
    }
}
