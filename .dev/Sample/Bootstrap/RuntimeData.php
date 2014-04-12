<?php
/**
 * Runtime Data
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

$runtime_data = new stdClass();

/** Only needed in the example */
$runtime_data->base_path = $pagination_base;

/** Get this from your Router */
$runtime_data->current_url            = $current_url;
$runtime_data->route                  = new stdClass();
$runtime_data->route->page            = $page_url;
$runtime_data->route->parameter_start = $parameter_start;

/** Your application must provide this pagination configuration */
$runtime_data->parameters                                 = new stdClass();
$runtime_data->parameters->display_items_per_page_count                 = 3;
$runtime_data->parameters->display_page_link_count        = 3;
$runtime_data->parameters->create_sef_url_indicator       = false;
$runtime_data->parameters->display_index_in_url_indicator = true;

/** Only needed in the example */
$runtime_data->include                    = new stdClass();
$runtime_data->include->theme_base_folder = $pagination_base . '.dev/Sample/Public/Theme';
$runtime_data->include->view_base_folder  = $pagination_base . '.dev/Sample/Views';

/** Composer Autoloader */
include $pagination_base . '/vendor/autoload.php';
