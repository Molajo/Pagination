<?php
/**
 * Example of how to use the Pagination Class in any PHP Application
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

/** 1. Routing: simulates the Router */
include __DIR__ . '/Route.php';

/** 2. Configuration Data */
include __DIR__ . '/RuntimeData.php';

/** 3. Get Mocked Data: could be any list of data */
include __DIR__ . '/MockData.php';

$data_instance = new \Molajo\Pagination\MockData(
        (int) $runtime_data->route->parameter_start,
        (int) $runtime_data->parameters->display_items_per_page_count
);

$mockdata = $data_instance->getData();

/** 4. Get Pagination Data (the main point!) */
$pagination_instance = new \Molajo\Pagination();

$row = $pagination_instance->getPaginationData(

    // Configuration: variables your application must provide
    $runtime_data->parameters->display_items_per_page_count,    // How many items are displayed on each page?
    $runtime_data->parameters->display_page_link_count,         // 3 in this example => << < 1 2 3 > >>
    $runtime_data->parameters->create_sef_url_indicator,        // Should SEF URLs be returned? true or false
    $runtime_data->parameters->display_index_in_url_indicator,  // Should index.php appear in the URL? true or false

    // Primary Data: the total number of rows that could have been returned for the primary data
    $data_instance->getTotalItemsCount(),

    // Router: data from your router to help build the URLs for the pagination links
    $runtime_data->route->page,                // URL for page on which paginated appears
    $runtime_data->route->parameter_start,     // Query parameter 'start', for example, "?start=3" or "/start/3"
    array()                                    // Other query parameters like "&tag=dog" or "/category/dog"
);

/** 5. Render Theme: List View uses $mockdata, while Pagination View uses $row data */
ob_start();
include $runtime_data->include->theme_base_folder . '/Index.phtml';
$rendered_page = ob_get_clean();

/** 6. Pass $rendered_page off to your response class */
echo $rendered_page;
