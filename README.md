DOMPDFModule
============

Master: [![Build Status](https://secure.travis-ci.org/raykolbe/DOMPDFModule.png?branch=master)](http://travis-ci.org/raykolbe/DOMPDFModule)

The DOMPDF module integrates the DOMPDF library with Zend Framework 2 with minimal
effort on the consumer's end.

## Requirements
  - [Zend Framework 2](http://www.github.com/zendframework/zf2)
  - [DOMPDF](https://github.com/raykolbe/dompdf)

## Installation
Installation of DOMPDFModule uses PHP Composer. For more information about
PHP Composer, please visit the official [PHP Composer site](http://getcomposer.org/).

#### Installation steps

  1. `cd my/project/directory`
  2. create a `composer.json` file with following contents:

     ```json
     {
         "require": {
             "dino/DOMPDFModule": "dev-master"
         }
     }
     ```
  3. install PHP Composer via `curl -s http://getcomposer.org/installer | php` (on windows, download
     http://getcomposer.org/installer and execute it with PHP)
  4. run `php composer.phar install`
  5. open `my/project/directory/config/application.config.php` and add the following key to your `modules`: 

     ```php
     'DOMPDFModule',
     ```
#### Configuration options
Copy `dino/DOMPDFModule/config/module.dompdf.local.php` to `my/project/directory/config/autoload/module.dompdf.local.php` and reference `dino/DOMPDFModule/config/module.config.php` for configration options that you can override.

## Usage

```php
<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController;
use DOMPDFModule\View\Model\PdfModel;

class ReportController extends ActionController
{
    public function monthlyReportPdfAction()
    {
        return new PdfModel(
            array(), // Variable assignments per Zend\View\Model\ViewModel
            array(
                'fileName' => 'monthly-report', // Optional; triggers PDF download, automatically appends ".pdf"
                'paperSize' => 'a4',
                'paperOrientation' => 'landscape'
            )
        );
    }
}
```

## To-do
  - Create tests
  - Add support for DOMPDF CLI