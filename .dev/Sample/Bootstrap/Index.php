<?php
/**
 * Example of how to use the Pagination Class in any PHP Application
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

/** 1. Routing: simulates the Router */
include __DIR__ . '/Route.php';

/** 2. Configuration Data */
include __DIR__ . '/RuntimeData.php';

/** 3. Get Mocked Data: could be any list of data */
include __DIR__ . '/MockData.php';

$data_instance = new \Molajo\Pagination\MockData(
    (int)$runtime_data->route->parameter_start,
    (int)$runtime_data->parameters->display_items_per_page_count
);

$mockdata = $data_instance->getData();

/**                                          */
/** 4. Get Pagination Data (the main point!) */
/**                                          */
$pagination_instance = new \Molajo\Pagination();

$row = $pagination_instance->getPaginationData(

// Configuration: variables your application must provide
    $runtime_data->parameters->display_items_per_page_count, // How many items are displayed on each page?
    $runtime_data->parameters->display_page_link_count, // 3 in this example => << < 1 2 3 > >>
    $runtime_data->parameters->create_sef_url_indicator, // Should SEF URLs be returned? true or false
    $runtime_data->parameters->display_index_in_url_indicator, // Should index.php appear in the URL? true or false

    // Primary Data: the total number of rows that could have been returned for the primary data
    $data_instance->getTotalItemsCount(),
    // Router: data from your router to help build the URLs for the pagination links
    $runtime_data->route->page, // URL for page on which paginated appears
    $runtime_data->route->parameter_start, // Query parameter 'start', for example, "?start=3" or "/start/3"
    array() // Other query parameters like "&tag=dog" or "/category/dog"
);

// The results of the previous command are stored in $row
//      and contain the following object used to render this display:
//
//  << <<  <<  1 2 3 4 5  >>  >> >>
//  A ...  B.  C........  D.  E....

// A ... << <<
// $row->first_page_number              = $this->getFirstPage();
// $row->first_page_link                = $this->getPageUrl('first');

// B ... <<
// $row->previous_page_number           = $this->getPrevPage();
// $row->previous_page_link             = $this->getPageUrl('previous');

// C ... used to loop thru 1 2 3 4 5
// $row->start_links_page_number        = $this->getStartLinksPage();
// $row->stop_links_page_number         = $this->getStopLinksPage();
// $row->page_links_array

// D ... >>
// $row->next_page_number               = $this->getNextPage();
// $row->next_page_link                 = $this->getPageUrl('next');

// E ... >> >>
// $row->last_page_number               = $this->getLastPage();
// $row->last_page_link                 = $this->getPageUrl('last');

// As the Pagination View shows, the start values can be used to determine "current" links
// $row->current_start_parameter_number = $this->getCurrentPage();
// $row->current_start_parameter_link   = $this->getPageUrl('current');
// $row->total_items                    = $this->getTotalItems();

/** 5. Render Theme: List View uses $mockdata, while Pagination View uses $row data */
ob_start();
include $runtime_data->include->theme_base_folder . '/Index.phtml';
$rendered_page = ob_get_clean();

/** 6. Pass $rendered_page off to your response class */
echo $rendered_page;
