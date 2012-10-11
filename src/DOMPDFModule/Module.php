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

namespace DOMPDFModule;

use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

/**
 * @todo On Composer post-install, copy dompdf_font_family_cache.dist.php
 *       to font directory as a non-dist file. Define __DOMPDF_FONT_CACHE_FILE
 *       to point to this new file.
 */
class Module implements ConsoleUsageProviderInterface
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__
                ),
            ),
        );
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
            'install system_fonts' => 'Installs system-installed fonts to DOMPDF font directory.',
            'install font_family <name> <file> [<bold_file>] [<italic_file>] [<bold_italic_file>]' => 'Installs font(s) to DOMPDF font directory',
            
            array('<name>'             , 'Font family name'),
            array('<file>'             , 'Path to .otf or .ttf regular font file'),
            array('<bold_file>'        , 'Path to .otf or .ttf bold font file'),
            array('<italic_file>'      , 'Path to .otf or .ttf italic font file'),
            array('<bold_italic_file>' , 'Path to .otf or .ttf bold italic font file')
        );
    }
}
