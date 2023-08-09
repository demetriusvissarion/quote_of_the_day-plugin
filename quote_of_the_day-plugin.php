<?php

/**
 * @package QuoteOfTheDayPlugin
 */
/*
Plugin Name: QuoteOfTheDay Plugin
Plugin URI: http://www.demetriusvissarion.com
Description: Custom Plugin
Version: 1.0.0
Author: Demetrius Vissarion
Author URI: https://github.com/demetriusvissarion
Licence: GPLv2 or later
Text Domain: quote_of_the_day-plugin
*/

/*
Copyright (c) 2023 Demetrius Vissarion

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

// If this file is called directly, abort
defined('ABSPATH') or die("Hello there");

// Include other plugin files
require_once plugin_dir_path(__FILE__) . 'admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'enqueue-scripts.php';
require_once plugin_dir_path(__FILE__) . 'quote-functions.php';
require_once plugin_dir_path(__FILE__) . 'quotes-management.php';
require_once plugin_dir_path(__FILE__) . 'update-widget-option.php';
require_once plugin_dir_path(__FILE__) . 'widget-class.php';
