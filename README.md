# Ultimo Phptpl
Template engine in PHP

## Features
* Helpers
* HelperDecorators
* Widgets

## Requirements
* PHP 5.3
* Ultimo CSS
* Ultimo HTML

## Usage

### module/view/scripts/viewscript.phtml
	Hello <?php echo $this->escape($this->bar) ?>
	Output of custom helper: <?php echo $this->fib(6) ?>

### module/view/helpers/Fib.php
	<?php
	namespace \module\view\helpers;
	
	class Fib extends \ultimo\phptpl\Helper {
		public function __invoke($n) {
			if ($n <= 1) {
				return $n;
			} else {
				return $this->__invoke($n-1) + $this->__invoke($n-2);
			}
		}
	}

### Rendering
	$engine = new \ultimo\phptpl\Engine();

	// specify where to find custom helpers and viewscripts
	$engine->addBasePath('module/view', 'module\view');

	// set a variable
	$engine->foo = 'bar';

	// render
	$engine->render('viewscript.phtml');