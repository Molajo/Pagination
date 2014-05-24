<?php
/**
 * Pagination
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo;

use CommonApi\Render\PaginationInterface;
use stdClass;

/**
 * Pagination
 *
 *   <<  <  1  2  3  >  >>
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Pagination implements PaginationInterface
{
    /**
     * Configuration setting for the number of items to display per page
     *
     * @var    int
     * @since  1.0
     */
    protected $display_items_per_page_count = 10;

    /**
     * Configuration setting for the number of page links to show
     *
     * @var    int
     * @since  1.0
     */
    protected $display_page_link_count = 5;

    /**
     * Configuration setting for the whether to create SEF URLs (true or false)
     *
     * @var    boolean
     * @since  1.0
     */
    protected $create_sef_url_indicator = false;

    /**
     * Configuration setting for whether to use index.php in the URL
     *
     * @var    boolean
     * @since  1.0
     */
    protected $display_index_in_url_indicator = false;

    /**
     * Base URL
     *
     * @var    int
     * @since  1.0
     */
    protected $visited_page_url;

    /**
     * Count of total items
     * -> ALL, includes previous, current, next
     *
     * @var    int
     * @since  1.0
     */
    protected $total_items;

    /**
     * Current value for "start=" URL parameter
     *
     * @var    int
     * @since  1.0
     */
    protected $current_start_parameter = 0;

    /**
     * URL Filters
     *
     * @var    array
     * @since  1.0
     */
    protected $other_query_parameters = array();

    /**
     * Last Page
     *
     * @var    int
     * @since  1.0
     */
    protected $last_page;

    /**
     * Start Page Number
     *
     * @var    int
     * @since  1.0
     */
    protected $start_links_page_number = 0;

    /**
     * Stop Page Number
     *
     * @var    int
     * @since  1.0
     */
    protected $stop_links_page_number = 0;

    /**
     * Get Pagination Row Object for input data to rendering
     *
     * << < 1 2 3 > >>
     *
     * @param   int     $total_items
     * @param   string  $visited_page_url
     * @param   int     $current_start_parameter
     * @param   array   $other_query_parameters
     * @param   int     $display_items_per_page_count
     * @param   int     $display_page_link_count
     * @param   boolean $create_sef_url_indicator
     * @param   boolean $display_index_in_url_indicator
     *
     * @since   1.0
     * @return  null|object
     */
    public function getPaginationData(
        $total_items,
        $visited_page_url,
        $current_start_parameter,
        $other_query_parameters,
        $display_items_per_page_count = 5,
        $display_page_link_count = 5,
        $create_sef_url_indicator = false,
        $display_index_in_url_indicator = true
    ) {
        $this->setPaginationDisplayValues(
            $total_items,
            $current_start_parameter,
            $display_items_per_page_count,
            $display_page_link_count
        );

        if ($this->total_items === 0) {
            return null;
        }

        $this->setPaginationUrlValues(
            $visited_page_url,
            $other_query_parameters,
            $create_sef_url_indicator,
            $display_index_in_url_indicator
        );

        return $this->createPaginationRow();
    }

    /**
     * Set Pagination Display Values
     *
     * @param   integer $total_items
     * @param   integer $current_start_parameter
     * @param   integer $display_items_per_page_count
     * @param   integer $display_page_link_count
     *
     * @return  $this
     * @since   1.0
     */
    protected function setPaginationDisplayValues(
        $total_items,
        $current_start_parameter,
        $display_items_per_page_count,
        $display_page_link_count
    ) {
        $this->total_items = (int)$total_items;

        $this->display_items_per_page_count = $display_items_per_page_count;

        if ((int)$this->display_items_per_page_count === 0) {
            $this->display_items_per_page_count = 9999999;
            $current_start_parameter            = 1;
            $display_page_link_count            = 0;
        }

        $this->setStartParameter($current_start_parameter);

        $this->setDisplayPageLinkCount($display_page_link_count);

        return $this;
    }

    /**
     * Set Start Parameter
     *
     * @param   integer $current_start_parameter
     *
     * @return  $this
     * @since   1.0
     */
    protected function setStartParameter($current_start_parameter)
    {
        if ((int)$current_start_parameter > 0) {
        } else {
            $current_start_parameter = 1;
        }

        if (($current_start_parameter * $this->display_items_per_page_count) > $this->total_items) {
            $current_start_parameter = 1;
        }

        $this->current_start_parameter = $current_start_parameter;

        return $this;
    }

    /**
     * Set Display Page Link Count
     *
     * @param   integer $display_page_link_count
     *
     * @return  $this
     * @since   1.0
     */
    protected function setDisplayPageLinkCount($display_page_link_count)
    {
        if ((int)$display_page_link_count < 1) {
            $display_page_link_count = 5;
        }

        $this->display_page_link_count = (int)$display_page_link_count;

        return $this;
    }

    /**
     * Set Pagination URL Values
     *
     * @param   string  $visited_page_url
     * @param   string  $other_query_parameters
     * @param   boolean $create_sef_url_indicator
     * @param   boolean $display_index_in_url_indicator
     *
     * @return  $this
     * @since   1.0
     */
    protected function setPaginationUrlValues(
        $visited_page_url,
        $other_query_parameters,
        $create_sef_url_indicator,
        $display_index_in_url_indicator
    ) {
        $this->setVisitedPageUrl($visited_page_url);
        $this->setOtherQueryParameters($other_query_parameters);
        $this->setSefUrlIndicators($create_sef_url_indicator, $display_index_in_url_indicator);
        $this->calculateStartAndStopLinks();

        return $this;
    }

    /**
     * Set Visited Page Url
     *
     * @param   string $visited_page_url
     *
     * @return  $this
     * @since   1.0
     */
    protected function setVisitedPageUrl($visited_page_url)
    {
        $this->visited_page_url = $visited_page_url;

        return $this;
    }

    /**
     * Set Other Query Parameters
     *
     * @param   string $other_query_parameters
     *
     * @return  $this
     * @since   1.0
     */
    protected function setOtherQueryParameters($other_query_parameters)
    {
        $this->other_query_parameters = $other_query_parameters;

        return $this;
    }

    /**
     * Set SEF Url Indicators
     *
     * @param   boolean $create_sef_url_indicator
     * @param   boolean $display_index_in_url_indicator
     *
     * @return  $this
     * @since   1.0
     */
    protected function setSefUrlIndicators($create_sef_url_indicator, $display_index_in_url_indicator)
    {
        $this->display_index_in_url_indicator = $display_index_in_url_indicator;

        if ($create_sef_url_indicator === true) {
            $this->create_sef_url_indicator = true;
        } else {
            $this->create_sef_url_indicator       = false;
            $this->display_index_in_url_indicator = true;
        }

        return $this;
    }

    /**
     * Calculate start and stop links
     *
     * @since   1.0
     * @return  $this
     */
    protected function calculateStartAndStopLinks()
    {
        $this->setPageBoundaries();

        if ($this->current_start_parameter - 1 > $this->start_links_page_number) {
            $this->start_links_page_number = $this->current_start_parameter - 1;
        }

        if ($this->start_links_page_number + $this->display_page_link_count - 1 > $this->last_page) {
            $this->stop_links_page_number  = $this->last_page;
            $this->start_links_page_number = $this->last_page - $this->display_page_link_count + 1;
        } else {
            $this->stop_links_page_number = $this->start_links_page_number + $this->display_page_link_count - 1;
        }

        $this->adjustBoundariesForContent();

        $this->display_page_link_count = $this->stop_links_page_number - $this->start_links_page_number + 1;

        return $this;
    }

    /**
     * Set Page Boundaries
     *
     * @since   1.0
     * @return  $this
     */
    protected function setPageBoundaries()
    {
        $this->last_page               = ceil($this->total_items / $this->display_items_per_page_count);
        $this->start_links_page_number = 1;
        $this->stop_links_page_number  = $this->last_page;

        return $this;
    }

    /**
     * Adjust Page Boundaries for Content
     *
     * @since   1.0
     * @return  $this
     */
    protected function adjustBoundariesForContent()
    {
        if ($this->start_links_page_number < 1) {
            $this->start_links_page_number = 1;
        }

        if ($this->stop_links_page_number > $this->last_page) {
            $this->stop_links_page_number = $this->last_page;
        }

        return $this;
    }

    /**
     * Create Pagination Row Object for rendering
     *
     * @since   1.0
     * @return  object
     */
    protected function createPaginationRow()
    {
        $row = new stdClass();

        $row->first_page_number              = $this->getFirstPage();
        $row->first_page_link                = $this->getPageUrl('first');
        $row->previous_page_number           = $this->getPrevPage();
        $row->previous_page_link             = $this->getPageUrl('previous');
        $row->current_start_parameter_number = $this->getCurrentPage();
        $row->current_start_parameter_link   = $this->getPageUrl('current');
        $row->next_page_number               = $this->getNextPage();
        $row->next_page_link                 = $this->getPageUrl('next');
        $row->last_page_number               = $this->getLastPage();
        $row->last_page_link                 = $this->getPageUrl('last');
        $row->total_items                    = $this->getTotalItems();
        $row->start_links_page_number        = $this->getStartLinksPage();
        $row->stop_links_page_number         = $this->getStopLinksPage();

        $row->page_links_array = array();

        for (
            $i = $row->start_links_page_number;
            $i < $row->stop_links_page_number + 1;
            $i++
        ) {
            $row->page_links_array[$i] = $this->getPageUrl($i);
        }

        return $row;
    }

    /**
     * Get the first page number (always page=1 unless no pages)
     *
     * @return  int
     * @since   1.0
     */
    protected function getFirstPage()
    {
        if ((int)$this->last_page === 0) {
            return 0;
        }

        return 1;
    }

    /**
     * Get the page number previous to the first displayed page number link
     *
     * @return  int
     * @since   1.0
     */
    protected function getPrevPage()
    {
        if ((1 > (int)$this->current_start_parameter - 1)) {
            return (int)$this->current_start_parameter;
        }

        return (int)$this->current_start_parameter - 1;
    }

    /**
     * Get the current page number
     *
     * @return  int
     * @since   1.0
     */
    protected function getCurrentPage()
    {
        return (int)$this->current_start_parameter;
    }

    /**
     * Get the page number following the last displayed page number link
     *
     * @return  int
     * @since   1.0
     */
    protected function getNextPage()
    {
        if ((int)$this->current_start_parameter + 1 > (int)$this->last_page) {
            return (int)$this->last_page;
        }

        return (int)$this->current_start_parameter + 1;
    }

    /**
     * Get the final page number
     *
     * @return  int
     * @since   1.0
     */
    protected function getLastPage()
    {
        return (int)$this->last_page;
    }

    /**
     * Get the first page number to use when looping through the display page number buttons
     *
     * @return  int
     * @since   1.0
     */
    protected function getStartLinksPage()
    {
        return (int)$this->start_links_page_number;
    }

    /**
     * Get the last page number to use when looping through the display page number buttons
     *
     * @return  int
     * @since   1.0
     */
    protected function getStopLinksPage()
    {
        return (int)$this->stop_links_page_number;
    }

    /**
     * Get the total number of items in the recordset (not just those displayed on the page)
     *
     * @return  int
     * @since   1.0
     */
    protected function getTotalItems()
    {
        return (int)$this->total_items;
    }

    /**
     * Get URL for specified key
     *
     * @param   mixed $page_number
     *
     * @return  string
     * @since   1.0
     */
    protected function getPageUrl($page_number)
    {
        if (strtolower($page_number) == 'first') {
            $page_number = $this->getFirstPage();
        } elseif (strtolower($page_number) == 'previous') {
            $page_number = $this->getPrevPage();
        } elseif (strtolower($page_number) == 'current') {
            $page_number = $this->getCurrentPage();
        } elseif (strtolower($page_number) == 'next') {
            $page_number = $this->getNextPage();
        } elseif (strtolower($page_number) == 'last') {
            $page_number = $this->getLastPage();
        } else {
            $page_number = (int)$page_number;
        }

        if ($page_number < 1) {
            $page_number = 1;
        }

        if ($page_number > $this->getLastPage()) {
            $page_number = $this->getLastPage();
        }

        if ($this->create_sef_url_indicator === true) {
            return $this->setPageUrlSef($page_number);
        }

        return $this->setPageUrlParameters($page_number);
    }

    /**
     * Set URL and Parameter Pairs
     *
     * @param   int $page_number
     *
     * @return  string
     * @since   1.0
     */
    protected function setPageUrlParameters($page_number)
    {
        return $this->setPageUrl($page_number, '&', '=');
    }

    /**
     * Set the SEF URL for Parameters
     *
     * @param   mixed $page_number
     *
     * @return  string
     * @since   1.0
     */
    protected function setPageUrlSef($page_number)
    {
        return $this->setPageUrl($page_number);
    }

    /**
     * Create the URL Parameters
     *
     * @param   mixed  $page_number
     * @param   string $connector
     * @param   string $separator
     *
     * @return  string
     * @since   1.0
     */
    protected function setPageUrl($page_number, $connector = '/', $separator = '/')
    {
        if ($connector === '&') {
            $first_connector = '?';
        } else {
            $first_connector = $connector;
        }

        $url = $this->visited_page_url;

        if ($this->display_index_in_url_indicator === true) {
            $url .= '/index.php';
        }

        $url .= $first_connector . 'start' . $separator . (int)$page_number;

        if (is_array($this->other_query_parameters) && count($this->other_query_parameters) > 0) {
            foreach ($this->other_query_parameters as $key => $value) {
                $url .= $connector . $key . $separator . $value;
            }
        }

        return $url;
    }
}
