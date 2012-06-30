<?php

namespace DOMPDFModuleTest\View\Strategy;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\EventManager\EventManager;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use DOMPDFModule\View\Model\PdfModel;
use Zend\View\Model\ViewModel;
use DOMPDFModule\View\Renderer\PdfRenderer;
use DOMPDFModule\View\Strategy\PdfStrategy;
use Zend\View\ViewEvent;

class PdfStrategyTest extends TestCase
{
    public function setUp()
    {
        $this->renderer = new PdfRenderer;
        $this->strategy = new PdfStrategy($this->renderer);
        $this->event    = new ViewEvent();
        $this->response = new HttpResponse();
    }

    public function testPdfModelSelectsPdfStrategy()
    {
        $this->event->setModel(new PdfModel());
        $result = $this->strategy->selectRenderer($this->event);
        $this->assertSame($this->renderer, $result);
    }
}