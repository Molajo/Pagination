<?php
/**
 * Runtime Data
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

$runtime_data                             = new stdClass();
$runtime_data->base_path                  = $pagination_base;
$runtime_data->current_url                = $current_url;
$runtime_data->route                      = new stdClass();
$runtime_data->route->page                = $page_url;
$runtime_data->route->parameter_start     = $parameter_start;
$runtime_data->parameters                 = new stdClass();
$runtime_data->parameters->items_per_page = 3;
$runtime_data->parameters->display_links  = 3;
$runtime_data->parameters->sef_url        = false;
$runtime_data->parameters->index_in_url   = true;
$runtime_data->include                    = new stdClass();
$runtime_data->include->theme_base_folder = $pagination_base . '.dev/Sample/Public/Theme';
$runtime_data->include->view_base_folder  = $pagination_base . '.dev/Sample/Views';

include $pagination_base . '/vendor/autoload.php';
