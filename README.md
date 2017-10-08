DOMPDFModule
============

[![Build Status](https://secure.travis-ci.org/raykolbe/DOMPDFModule.png?branch=master)](http://travis-ci.org/raykolbe/DOMPDFModule) [![Code Climate](https://codeclimate.com/github/raykolbe/DOMPDFModule/badges/gpa.svg)](https://codeclimate.com/github/raykolbe/DOMPDFModule) [![Test Coverage](https://codeclimate.com/github/raykolbe/DOMPDFModule/badges/coverage.svg)](https://codeclimate.com/github/raykolbe/DOMPDFModule/coverage) [![Total Downloads](https://poser.pugx.org/dino/dompdf-module/downloads)](https://packagist.org/packages/dino/dompdf-module) [![License](https://poser.pugx.org/dino/dompdf-module/license)](https://packagist.org/packages/dino/dompdf-module)

The DOMPDF module integrates the DOMPDF library with Zend Framework 2 with minimal effort on the consumer's end.

## Requirements
  - [Zend Framework 2](http://www.github.com/zendframework/zf2)

## Installation
Installation of DOMPDFModule uses PHP Composer. For more information about
PHP Composer, please visit the official [PHP Composer site](http://getcomposer.org/).

#### Installation steps

  1. `cd my/project/directory`
  2. create a `composer.json` file with following contents:

     ```json
     {
         "require": {
             "dino/dompdf-module": "dev-master"
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
You can override options via the `dompdf_module` key in your local or global config files. See DOMPDFModule/config/module.config.php for config options.

## Usage

```php
<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DOMPDFModule\View\Model\PdfModel;

class ReportController extends AbstractActionController
{
    public function monthlyReportPdfAction()
    {
        $pdf = new PdfModel();
        $pdf->setOption('fileName', 'monthly-report');            // "pdf" extension is automatically appended
        $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT); // Triggers browser to prompt "save as" dialog
        $pdf->setOption('paperSize', 'a4');                       // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'landscape');         // Defaults to "portrait"
        
        // To set view variables
        $pdf->setVariables(array(
          'message' => 'Hello'
        ));
        
        return $pdf;
    }
}
```
## Development
So you want to contribute? Fantastic! Don't worry, it's easy. Local builds, tests, and code quality checks can be executed using [Docker](https://www.docker.com/).

### Quick Start
1. Install [Docker CE](https://www.docker.com/community-edition).
2. Run the following from your terminal:

```
docker build -t dino/dompdf-module .
docker run -v composer-cache:/var/lib/composer -v ${PWD}:/opt/app dino/dompdf-module
```
    
Super easy, right? Here's a quick walk through as to what's going on.

* `docker build -t dino/dompdf-module .` builds a docker image that will be used for each run (i.e. each time `docker run` is executed) and tags it with the name `dino/dompdf-module`.
* `docker run -v composer-cache:/var/lib/composer -v ${PWD}:/opt/app dino/dompdf-module` runs the default build in a new Docker container derived from the image tagged `dino/dompdf-module`. The root of the project and PHP Composer cache volume are mounted so that artifacts generated during the build process are available to you on your local machine.

**Note:** You only need to run the first command once in order to build the image. The second command is what executes the build (build, tests, code quality checks, etc.).

### Other Supported PHP Versions
By default, builds executed using Docker are done so using the [latest stable version of PHP](http://php.net/supported-versions.php). If you're adventurous you can execute builds against other [supported versions](http://php.net/supported-versions.php) of PHP.

**PHP 5.6**

```
docker build --build-arg PHP_VERSION=5.6 --tag dino/dompdf-module-php56 .
docker run -v composer-cache:/var/lib/composer -v ${PWD}:/opt/app dino/dompdf-module-php56
```

## To-do
  - Add command line support.
