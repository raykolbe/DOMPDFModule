<?php

namespace DOMPDFModule\Controller;

use Zend\Mvc\Controller\AbstractActionController as Controller;
use DOMPDFModule\Font\FontFamilyInstaller;
use Font_Metrics as FontMetrics;

class CommandLineController extends Controller
{
    public function installFontFamilyAction()
    {
        $params = $this->plugin('params');
        $fontFamily = $params->fromRoute('name', false);
        $normalFontPath = $params->fromRoute('file', false);
        
        $installer = new FontFamilyInstaller($fontFamily, $normalFontPath);
        
        if ($bold = $params->fromRoute('bold_file', false)) {
            $installer->queueFont($bold, FontFamilyInstaller::FONT_TYPE_BOLD);
        }
        
        if ($italic = $params->fromRoute('italic_file', false)) {
            $installer->queueFont($italic, FontFamilyInstaller::FONT_TYPE_ITALIC);
        }
        
        if ($boldItalic = $params->fromRoute('bold_italic_file', false)) {
            $installer->queueFont($boldItalic, FontFamilyInstaller::FONT_TYPE_BOLD_ITALIC);
        }
        
        $installer->install();
    }
    
    public function installSystemFontsAction()
    {
        $fonts = FontMetrics::get_system_fonts();
        
        foreach ($fonts as $family => $files) {
            if (!isset($files['normal'])) {
                continue;
            }

            $installer = new FontFamilyInstaller($family, $files["normal"]);
            
            if (isset($files["bold"])) {
                $installer->queueFont($files['bold'], FontFamilyInstaller::FONT_TYPE_BOLD);
            }
            
            if (isset($files["italic"])) {
                $installer->queueFont($files['italic'], FontFamilyInstaller::FONT_TYPE_ITALIC);
            }
            
            if (isset($files["bold_italic"])) {
                $installer->queueFont($files['bold_italic'], FontFamilyInstaller::FONT_TYPE_BOLD_ITALIC);
            }
            
            $installer->install();
        }
    }
}