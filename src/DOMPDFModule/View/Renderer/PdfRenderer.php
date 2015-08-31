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

namespace DOMPDFModule\View\Renderer;

use Zend\View\Renderer\RendererInterface as Renderer;
use Zend\View\Resolver\ResolverInterface as Resolver;
use DOMPDF;

class PdfRenderer implements Renderer
{
    private $dompdf = null;
    private $resolver = null;
    private $htmlRenderer = null;
    private $yPageCounter = null;
    private $xPageCounter = null;
    
    public function setHtmlRenderer(Renderer $renderer)
    {
        $this->htmlRenderer = $renderer;
        return $this;
    }
    
    public function getHtmlRenderer()
    {
        return $this->htmlRenderer;
    }
    
    public function setEngine(DOMPDF $dompdf)
    {
        $this->dompdf = $dompdf;
        return $this;
    }
    
    public function getEngine()
    {
        return $this->dompdf;
    }
    
    /**
     * Renders values as a PDF
     *
     * @param  string|Model $name The script/resource process, or a view model
     * @param  null|array|\ArrayAccess Values to use during rendering
     * @return string The script output.
     */
    public function render($nameOrModel, $values = null)
    {
        $html = $this->getHtmlRenderer()->render($nameOrModel, $values);
        
        $paperSize = $nameOrModel->getOption('paperSize');
        $paperOrientation = $nameOrModel->getOption('paperOrientation');
        $basePath = $nameOrModel->getOption('basePath');
        $textPageCounter = $nameOrModel->getOption('textPageCounter');
        $positionPageCounter = $nameOrModel->getOption('positionPageCounter');
        $fontPageCounter = \Font_Metrics::get_font("helvetica", "normal");
        $sizeFontPageCounter = 9;
        $width_text_counter = mb_strwidth($textPageCounter);
        
        $pdf = $this->getEngine();
        $pdf->set_paper($paperSize, $paperOrientation);
        $pdf->set_base_path($basePath);
        
        $pdf->load_html($html);
        $pdf->render();

        if($positionPageCounter != 'none') {
            $canvas = $pdf->get_canvas();
            $this->calculatePositionCounterPage($positionPageCounter, $canvas->get_width(), $canvas->get_height(), $width_text_counter);
            $canvas->page_text($this->xPageCounter, $this->yPageCounter, $textPageCounter, $fontPageCounter, $sizeFontPageCounter);
        }
        
        return $pdf->output();
    }

    public function setResolver(Resolver $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * Calculates coordinates x and y where the text 'textPageCounter' (property) where will be printed
     * 
     * @param string  $positionPageCounter The fixed position
     * @param integer $page_width          The width of page
     * @param integer $page_height         The height of page
     * @param string  $width_text          The width value calculated of 'textPageCounter' (property)
     */
    private function calculatePositionCounterPage($positionPageCounter, $page_width, $page_height, $width_text)
    {
        switch ($positionPageCounter)
        {
            case 'top-center':
                $this->yPageCounter = 15;
                $this->xPageCounter = $page_width/2 - $width_text/2;
                break;
            case 'top-left':
                $this->yPageCounter = 15;
                $this->xPageCounter = 15;
                break;
            case 'top-right':
                $this->yPageCounter = 15;
                $this->xPageCounter = $page_width - 15 - $width_text;
                break;
            case 'bottom-left':
                $this->yPageCounter = $page_height - 24;
                $this->xPageCounter = 15;
                break;
            case 'bottom-center':
                $this->yPageCounter = $page_height - 24;
                $this->xPageCounter = $page_width/2 - $width_text/2;
                break;
            case 'bottom-right':
                $this->yPageCounter = $page_height - 24;
                $this->xPageCounter = $page_width - 15 - $width_text;
                break;
            default:
                $this->yPageCounter = $page_height - 24;
                $this->xPageCounter = $page_width - 15 - $width_text;
                break;
         }
    }
}
