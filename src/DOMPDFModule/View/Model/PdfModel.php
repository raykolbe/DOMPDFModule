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

namespace DOMPDFModule\View\Model;

use Zend\View\Model\ViewModel;

class PdfModel extends ViewModel
{
    const DISPLAY_INLINE = 'inline';
    const DISPLAY_ATTACHMENT = 'attachment';
    const DEFAULT_FILE_NAME = 'untitled.pdf';

    /**
     * Renderer options
     * @var array
     */
    protected $options = [
        'paperSize' => '8x11',
        'paperOrientation' => 'portrait',
        'basePath' => '/',
        'fileName' => self::DEFAULT_FILE_NAME,
        'display' => self::DISPLAY_INLINE
    ];

    /**
     * PDF probably won't need to be captured into a
     * a parent container by default.
     *
     * @var string
     */
    protected $captureTo = null;

    /**
     * PDF is usually terminal
     *
     * @var bool
     */
    protected $terminate = true;
}
