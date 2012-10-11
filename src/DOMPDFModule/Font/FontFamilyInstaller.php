<?php

namespace DOMPDFModule\Font;

use \SplFileInfo as FileInfo;
use \Font_Metrics as FontMetrics;
use \Font as FontLib;

/**
 * @todo Add support for normal font with suffix "-Normal", which will allow for
 *       proper automatic font additions of bold, italic, bold italic.
 * 
 * @see http://v1.jontangerine.com/silo/typography/naming/ for naming scheme.
 */
class FontFamilyInstaller
{
    const FONT_TYPE_NORMAL = 'normal';
    const FONT_TYPE_BOLD = 'bold';
    const FONT_TYPE_ITALIC = 'italic';
    const FONT_TYPE_BOLD_ITALIC = 'bold_italic';
    
    protected $family = null;
    protected $normalFontPath = null;
    protected $boldFontPath = null;
    protected $italicFontPath = null;
    protected $boldItalicFontPath = null;
    protected $fontDirectory = null;
    
    protected static $validExtensions = array('ttf', 'otf');
    protected static $additionalFontPatterns = array(
        'bold'        => array('-Bold', '_Bold', 'b', 'B', 'bd', 'BD'),
        'italic'      => array('-Oblique', '_Italic', 'i', 'I'),
        'bold_italic' => array('-BoldOblique', '_Bold_Italic', 'bi', 'BI', 'ib', 'IB'),
    );
    
    public function __construct($family, $normalFontPath)
    {
        $this->family = $family;
        $this->queueFont($normalFontPath);
    }
    
    /**
     * 
     * @todo Rename to setInstallDirectory
     * 
     * @param type $path
     * @return \DOMPDFModule\Font\FontFamilyInstaller
     * @throws \RuntimeException
     */
    public function setFontDirectory($path)
    {
        $this->fontDirectory = new FileInfo($path);
        if (!$this->fontDirectory->isDir()) {
            throw new \RuntimeException('Font directory is not a directory at all.');
        }
        
        if (!$this->fontDirectory->isWritable()) {
            throw new \RuntimeException('Font directory is not writable.');
        }
        
        return $this;
    }
    
    /**
     * 
     * @todo Rename to getInstallDirectory
     * 
     * @return type
     */
    public function getFontDirectory()
    {
        if (!isset($this->fontDirectory)) {
            $this->setFontDirectory(DOMPDF_FONT_DIR);
        }
        return $this->fontDirectory;
    }
    
    /**
     * 
     * @todo Potentially rename to queueFontForInstallation
     * 
     * @param type $path
     * @param type $type
     * @return \DOMPDFModule\Font\FontFamilyInstaller
     * @throws Exception\InvalidFontFile
     */
    public function queueFont($path, $type = self::FONT_TYPE_NORMAL)
    {
        $font = new FileInfo($path);
        if (!$font->isReadable()) {
            throw new Exception\InvalidFontFile(sprintf(
                "'%s' is not readable.",
                $path
            ));
        }
        
        if (!in_array($font->getExtension(), static::$validExtensions)) {
            throw new Exception\InvalidFontFile(sprintf(
                "Invalid font extension '%s' provided. Only ttf and otf are supported",
                $font->getExtension()
            ));
        }
        
        switch ($type) {
            case static::FONT_TYPE_NORMAL;
                if (isset($this->normalFontPath)) {
                    throw new Exception\InvalidFontFile(
                        "Normal font can not be queued after installer instantiation."
                    );
                }
                $this->normalFontPath = $font;
                break;
            case static::FONT_TYPE_BOLD;
                $this->boldFontPath = $font;
                break;
            case static::FONT_TYPE_ITALIC;
                $this->italicFontPath = $font;
                break;
            case static::FONT_TYPE_BOLD_ITALIC;
                $this->boldItalicFontPath = $font;
                break;
            default :
                throw new Exception\InvalidFontFile("Invalid type specified.");
        }
        
        return $this;
    }
    
    public function install()
    {
        if (!$this->boldFontPath) {
            $this->boldFontPath = $this->findAdditionalFont(static::FONT_TYPE_BOLD);
        }
        
        if (!$this->italicFontPath) {
            $this->italicFontPath = $this->findAdditionalFont(static::FONT_TYPE_ITALIC);
        }
        
        if (!$this->boldItalicFontPath) {
            $this->boldItalicFontPath = $this->findAdditionalFont(static::FONT_TYPE_BOLD_ITALIC);
        }
        
        $fonts = array(
            'normal' => $this->normalFontPath,
            'bold' => $this->boldFontPath,
            'italic' => $this->italicFontPath,
            'bold_italic' => $this->boldItalicFontPath
        );
        
        FontMetrics::init();
        
        $fontMap = array();
        
        foreach ($fonts as $type => $info) {
            if (null == $info) {
                /**
                 * Font not found, default to using the 'normal' font as an additional font.
                 */
                $fontMap[$type] = $this->getFontDirectory()->getRealPath() .
                                  DIRECTORY_SEPARATOR .
                                  $fonts['normal']->getBasename('.' . $destination->getExtension());
                continue;
            }
            
            $destination = new FileInfo($this->getFontDirectory()->getRealPath() . DIRECTORY_SEPARATOR . $info->getBasename());
            
            if (!copy($info->getRealPath(), $destination->getPath() . DIRECTORY_SEPARATOR .$destination->getBasename())) {
                throw new \RuntimeException(sprintf(
                    "Unable to copy '%s' to '%s'",
                    $info->getRealPath(),
                    $destination->getPath() . DIRECTORY_SEPARATOR . $destination->getBasename()
                ));
            }
            
            //echo "Generating Adobe Font Metrics for $entry_name...\n";
            $entry = $destination->getPath() .
                     DIRECTORY_SEPARATOR .
                     $destination->getBasename('.' . $destination->getExtension());
            
            $font = FontLib::load($destination->getPath() .'/'.$destination->getBasename());
            $font->saveAdobeFontMetrics($entry . '.ufm');

            $fontMap[$type] = $entry;
        }
        
        FontMetrics::set_font_family($this->family, $fontMap);
        FontMetrics::save_font_families();
    }
    
    protected function findAdditionalFont($type)
    {   
        foreach (static::$additionalFontPatterns[$type] as $pattern) {
            $path = $this->normalFontPath->getPath() .
                    DIRECTORY_SEPARATOR .
                    $this->normalFontPath->getBasename('.' . $this->normalFontPath->getExtension()) .
                    $pattern .
                    '.' . $this->normalFontPath->getExtension();

            $font = new FileInfo($path);

            if (!$font->isReadable()) {
                continue;
            }

            return $font;
        }
        return null;
    }
}
