<?php
/**
 * Entry Point to Molajito Sample
 *
 * @package    Molajito
 * @link       https://github.com/Molajo/Molajito
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
$path            = __DIR__;
$pagination_base = substr($path, 0, strlen($path) - 18);
include $pagination_base . '/.dev/Sample/Bootstrap/Index.php';
