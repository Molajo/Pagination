<?php
/**
 * Pagination
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
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
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
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
    protected $start_page_number = 0;

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
     * Page Array
     *
     * @var    array
     * @since  1.0
     */
    protected $page_array = array('First', 'Previous', 'Current', 'Next', 'Last');

    /**
     * Page Array
     *
     * @var    array
     * @since  1.0
     */
    protected $properties_array
        = array(
            'display_items_per_page_count',
            'display_page_link_count',
            'create_sef_url_indicator',
            'display_index_in_url_indicator',
            'visited_page_url',
            'total_items',
            'last_page',
            'start_page_number',
            'other_query_parameters',
            'start_links_page_number',
            'stop_links_page_number'
        );

    /**
     * Get Pagination Row Object for input data to rendering
     *
     * << < 1 2 3 > >>
     *
     * @param   int     $total_items
     * @param   string  $visited_page_url
     * @param   int     $start_page_number
     * @param   array   $other_query_parameters
     * @param   int     $display_items_per_page_count
     * @param   int     $display_page_link_count
     * @param   boolean $create_sef_url_indicator
     * @param   boolean $display_index_in_url_indicator
     *
     * @since   1.0.0
     * @return  null|stdClass
     */
    public function getPaginationData(
        $total_items,
        $visited_page_url,
        $start_page_number,
        $other_query_parameters,
        $display_items_per_page_count = 5,
        $display_page_link_count = 5,
        $create_sef_url_indicator = false,
        $display_index_in_url_indicator = true
    ) {
        $this->total_items                    = $total_items;
        $this->visited_page_url               = $visited_page_url;
        $this->start_page_number              = $start_page_number;
        $this->other_query_parameters         = $other_query_parameters;
        $this->display_items_per_page_count   = $display_items_per_page_count;
        $this->display_page_link_count        = $display_page_link_count;
        $this->create_sef_url_indicator       = $create_sef_url_indicator;
        $this->display_index_in_url_indicator = $display_index_in_url_indicator;

        return $this->driver();
    }

    /**
     * Calculate page values, render pagination row
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function driver()
    {
        if ((int)$this->total_items === 0) {
            return null;
        }

        $this->calculateValues();

        return $this->createPaginationRow();
    }

    /**
     * Pass properties into Calculate Class and return calculations
     *
     *
     * @return  Pagination
     * @since   1.0.0
     */
    protected function calculateValues()
    {
        $options = array();
        foreach ($this->properties_array as $key) {
            $options[$key] = $this->$key;
        }

        $calculate    = new Calculations();
        $calculations = $calculate->setValues($this->properties_array, $options);

        foreach ($this->properties_array as $key) {
            $this->$key = $calculations[$key];
        }

        return $this;
    }

    /**
     * Create Pagination Row Object for rendering
     *
     * @since   1.0.0
     * @return  stdClass
     */
    protected function createPaginationRow()
    {
        $row = new stdClass();
        foreach ($this->page_array as $value) {
            $row = $this->getPageUrlDriver($value, $row);
        }

        $row->total_items             = $this->getTotalItems();
        $row->start_links_page_number = $this->getStartLinksPage();
        $row->stop_links_page_number  = $this->getStopLinksPage();

        $row->page_links_array = array();

        for ($i = $row->start_links_page_number; $i < $row->stop_links_page_number + 1; $i++) {
            $page_link                 = $this->getPageUrl($i);
            $row->page_links_array[$i] = $page_link[1];
        }

        return $row;
    }

    /**
     * Get URL for specified key
     *
     * @param   mixed    $value
     * @param   stdClass $row
     *
     * @return  stdClass
     * @since   1.0.0
     */
    protected function getPageUrlDriver($value, $row)
    {
        $page_link = $this->getPageUrl($value);

        $column       = strtolower($value) . '_page_number';
        $row->$column = $page_link[0];

        $column       = strtolower($value) . '_page_link';
        $row->$column = $page_link[1];

        return $row;
    }

    /**
     * Get URL for specified key
     *
     * @param   mixed $page_number
     *
     * @return  array
     * @since   1.0.0
     */
    protected function getPageUrl($page_number)
    {
        if (is_numeric($page_number) && $page_number > 0) {
            $page_number = (int)$page_number;
        } else {
            $method      = 'get' . $page_number . 'Page';
            $page_number = $this->$method();
        }

        $page_number = $this->adjustPageNumberForContent($page_number);

        if ($this->create_sef_url_indicator === true) {
            $page_url = $this->setPageUrlSef($page_number);
        } else {
            $page_url = $this->setPageUrlParameters($page_number);
        }

        return (array($page_number, $page_url));
    }

    /**
     * Adjust Page Number for Content
     *
     * @param   integer $page_number
     *
     * @return  int
     * @since   1.0.0
     */
    protected function adjustPageNumberForContent($page_number)
    {
        if ($page_number < 1) {
            $page_number = 1;
        }

        if ($page_number > $this->getLastPage()) {
            $page_number = $this->getLastPage();
        }

        return $page_number;
    }

    /**
     * Get the first page number (always page=1 unless no pages)
     *
     * @return  int
     * @since   1.0.0
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
     * @since   1.0.0
     */
    protected function getPreviousPage()
    {
        if ((1 > (int)$this->start_page_number - 1)) {
            return (int)$this->start_page_number;
        }

        return (int)$this->start_page_number - 1;
    }

    /**
     * Get the current page number
     *
     * @return  int
     * @since   1.0.0
     */
    protected function getCurrentPage()
    {
        return (int)$this->start_page_number;
    }

    /**
     * Get the page number following the last displayed page number link
     *
     * @return  int
     * @since   1.0.0
     */
    protected function getNextPage()
    {
        if ((int)$this->start_page_number + 1 > (int)$this->last_page) {
            return (int)$this->last_page;
        }

        return (int)$this->start_page_number + 1;
    }

    /**
     * Get the final page number
     *
     * @return  int
     * @since   1.0.0
     */
    protected function getLastPage()
    {
        return (int)$this->last_page;
    }

    /**
     * Get the first page number to use when looping through the display page number buttons
     *
     * @return  int
     * @since   1.0.0
     */
    protected function getStartLinksPage()
    {
        return (int)$this->start_links_page_number;
    }

    /**
     * Get the last page number to use when looping through the display page number buttons
     *
     * @return  int
     * @since   1.0.0
     */
    protected function getStopLinksPage()
    {
        return (int)$this->stop_links_page_number;
    }

    /**
     * Get the total number of items in the recordset (not just those displayed on the page)
     *
     * @return  int
     * @since   1.0.0
     */
    protected function getTotalItems()
    {
        return (int)$this->total_items;
    }

    /**
     * Set URL and Parameter Pairs
     *
     * @param   int $page_number
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setPageUrlParameters($page_number)
    {
        return $this->setPageUrl($page_number, '?', '&', '=');
    }

    /**
     * Set the SEF URL for Parameters
     *
     * @param   integer $page_number
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setPageUrlSef($page_number)
    {
        return $this->setPageUrl($page_number);
    }

    /**
     * Create the URL Parameters
     *
     * @param   mixed  $page_number
     * @param   string $first_connector
     * @param   string $connector
     * @param   string $separator
     *
     * @return  string
     * @since   1.0.0
     */
    protected function setPageUrl($page_number, $first_connector = '/', $connector = '/', $separator = '/')
    {
        $url = $this->visited_page_url;

        if ($this->display_index_in_url_indicator === true) {
            $url .= '/index.php';
        }

        $url .= $first_connector . 'start' . $separator . (int)$page_number;

        if (count($this->other_query_parameters) > 0) {
            foreach ($this->other_query_parameters as $key => $value) {
                $url .= $connector . $key . $separator . $value;
            }
        }

        return $url;
    }
}
