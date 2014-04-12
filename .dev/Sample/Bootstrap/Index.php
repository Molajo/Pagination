<?php
/**
 * Bootstrap Frontcontroller
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
if (! defined('PHP_VERSION_ID')) {
    $version = explode('.', phpversion());
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

/** 1. Routing */
include __DIR__ . '/Route.php';

/** 2. Configuration Data */
include __DIR__ . '/RuntimeData.php';

/** 3. Get Mocked Data */
include __DIR__ . '/MockData.php';

$data_instance = new \Molajo\Pagination\MockData(
        (int) $runtime_data->route->parameter_start,
        (int) $runtime_data->parameters->items_per_page
);

$mockdata = $data_instance->getData();

/** 4. Get Pagination Data */
$pagination_instance = new \Molajo\Pagination();

$pagination_data = $pagination_instance->getPaginationData(
    $runtime_data->route->page,                                     // URL for page on which paginated appears
    array(),                                                        // URL Query Parameters (other than 'start')
    $data_instance->getTotalItemsCount(),                           // Total items in full resultset for data
    $runtime_data->parameters->items_per_page,                      // Number of items per page
    $runtime_data->parameters->display_links,                       // Number of page number "links" to display
    $current_page = (int) $runtime_data->route->parameter_start,    // Current 'page' -- start parameter value
    $runtime_data->parameters->sef_url,                             // Use SEF Urls? True or false
    $runtime_data->parameters->index_in_url                         // Include index.php in URL? True or false
);

/** 6. Render Theme, passing in data */
ob_start();
include $runtime_data->include->theme_base_folder . '/Index.phtml';
$rendered_page = ob_get_clean();

/** Pass $rendered_page off to your response class */
echo $rendered_page;
