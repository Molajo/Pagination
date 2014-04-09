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

/**
 * Pagination
 *
 * To get "Prev/Next" type pagination, set $per_page to 1
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Pagination implements PaginationInterface
{
    /**
     * Data - numerically indexed array items
     *
     * @var    array
     * @since  1.0
     */
    protected $data;

    /**
     * Base URL for pagination page
     *
     * @var    int
     * @since  1.0
     */
    protected $page_url;

    /**
     * URL Filters
     *
     * @var    array
     * @since  1.0
     */
    protected $query_parameters = array();

    /**
     * Total Items (could include more than the pagination set)
     *
     * @var    int
     * @since  1.0
     */
    protected $total_items;

    /**
     * Items per page
     *
     * @var    int
     * @since  1.0
     */
    protected $per_page = 10;

    /**
     * Number of page links to show
     *
     * @var    int
     * @since  1.0
     */
    protected $display_links = 5;

    /**
     * Get the first page number to use when
     * looping through the display page buttons
     *
     * @var    int
     * @since  1.0
     */
    protected $start_display_page;

    /**
     * Get the last page number to use when
     * looping through the display page buttons
     *
     * @var    int
     * @since  1.0
     */
    protected $stop_display_page;

    /**
     * Current Page minus 1
     *
     * @var    int
     * @since  1.0
     */
    protected $page = 0;

    /**
     * Last Page
     *
     * @var    int
     * @since  1.0
     */
    protected $last_page;

    /**
     * SEF URLs (true or false)
     *
     * @var    boolean
     * @since  1.0
     */
    protected $sef_url = false;

    /**
     * Use index.php
     *
     * @var    boolean
     * @since  1.0
     */
    protected $index_in_url = false;

    /**
     * Construct
     *
     * @since  1.0
     */
    public function __construct()
    {

    }

    /**
     * Set pagination values
     *
     * @param  array   $data             Data to be displayed (not full results)
     * @param  string  $page_url         URL for page on which paginated appears
     * @param  array   $query_parameters URL Query Parameters (other than page)
     * @param  int     $total_items      Total items in full resultset for data
     * @param  int     $per_page         Number of items per page
     * @param  int     $display_links    Number of page number "buttons" to show
     * @param  int     $page             Current page
     * @param  boolean $sef_url          Use SEF URLs?
     * @param  boolean $index_in_url     Use index.php value in URL?
     *
     * @since  1.0
     */
    public function setPagination(
        array $data = array(),
        $page_url,
        array $query_parameters = array(),
        $total_items,
        $per_page,
        $display_links,
        $page,
        $sef_url = false,
        $index_in_url = false
    ) {
        $this->data             = $data;
        $this->page_url         = $page_url;
        $this->query_parameters = $query_parameters;
        $this->total_items      = $total_items;

        if ((int)$per_page === 0) {
            $per_page = 9999999;
            $page     = 0;
        }
        $this->per_page = $per_page;

        if ((int)$page > $this->total_items) {
            $page = 0;
        }

        $this->page               = $page;
        $this->last_page          = ceil($this->total_items / $per_page);
        $this->display_links      = $display_links;
        $this->start_display_page = 1;
        $this->stop_display_page  = $this->last_page;
        $temp                     = ceil($this->last_page / $this->display_links);

        for ($i = 1; $i < $temp + 1; $i ++) {
            if (($i * $this->display_links) + 1 >= $page
                && $page >= ($i * $this->display_links) - $this->display_links + 1
            ) {
                $this->start_display_page = ($i * $this->display_links) - $this->display_links + 1;
                $this->stop_display_page  = ($i * $this->display_links) + 1;
            }
        }

        if ($sef_url === true) {
            $this->sef_url = true;
            if ($index_in_url === true) {
                $this->index_in_url = true;
            } else {
                $this->index_in_url = false;
            }
        } else {
            $this->sef_url      = false;
            $this->index_in_url = true;
        }
    }

    /**
     * Get the first page number (always page=1)
     *
     * @return  int
     * @since   1.0
     */
    public function getFirstPage()
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
    public function getPrevPage()
    {
        if (((int)$this->start_display_page - 1) > 1) {
            return (int)$this->start_display_page;
        }

        return 1;
    }

    /**
     * Get the first page number to use when looping through the display page number buttons
     *
     * @return  int
     * @since   1.0
     */
    public function getStartDisplayPage()
    {
        return $this->start_display_page;
    }

    /**
     * Get the current page number
     *
     * @return  int
     * @since   1.0
     */
    public function getCurrentPage()
    {
        return (int)$this->page + 1;
    }

    /**
     * Get the last page number to use when looping through the display page number buttons
     *
     * @return  int
     * @since   1.0
     */
    public function getStopDisplayPage()
    {
        return $this->stop_display_page;
    }

    /**
     * Get the page number following the last displayed page number link
     *
     * @return  int
     * @since   1.0
     */
    public function getNextPage()
    {
        if ((int)$this->stop_display_page > (int)$this->last_page) {
            return (int)$this->last_page;
        }

        return (int)$this->stop_display_page;
    }

    /**
     * Get the final page number
     *
     * @return  int
     * @since   1.0
     */
    public function getLastPage()
    {
        return (int)$this->last_page;
    }

    /**
     * Get data paginated
     *
     * @return  array
     * @since   1.0
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the total number of items in the recordset (not just those displayed on the page)
     *
     * @return  int
     * @since   1.0
     */
    public function getTotalItems()
    {
        return (int)$this->total_items;
    }

    /**
     * Get the URL for the specified key
     *
     *  - Use a numeric page number
     *  - Or, use a specific key value: first, previous, current, next, or last
     *
     * @param   mixed $page
     *
     * @return  string
     * @since   1.0
     */
    public function getPageUrl($page)
    {
        if (strtolower($page) == 'first') {
            $page = $this->getFirstPage();
        } elseif (strtolower($page) == 'previous') {
            $page = $this->getPrevPage();
        } elseif (strtolower($page) == 'current') {
            $page = $this->getCurrentPage();
        } elseif (strtolower($page) == 'next') {
            $page = $this->getNextPage();
        } elseif (strtolower($page) == 'last') {
            $page = $this->getLastPage();
        } else {
            $page = (int)$page;
        }

        if ($page < 1) {
            $page = 1;
        }

        if ($page > $this->getLastPage()) {
            $page = $this->getLastPage();
        }

        if ($this->sef_url === true) {
            return $this->setPageUrlSef($page);
        }

        return $this->setPageUrlParameters($page);
    }

    /**
     * Set the Parameterized URL for the specified key
     *
     * @param   mixed $page
     *
     * @return  string
     * @since   1.0
     */
    protected function setPageUrlParameters($page)
    {
        $url = $this->page_url;

        if ($this->index_in_url === true) {
            $url .= '/index.php';
        }

        $url .= '?start=' . (int)$page;

        if (is_array($this->query_parameters) && count($this->query_parameters) > 0) {
            foreach ($this->query_parameters as $key => $value) {
                $url .= '&' . $key . '=' . $value;
            }
        }

        return $url;
    }

    /**
     * Set the SEF URL for the specified key
     *
     * @param   mixed $page
     *
     * @return  string
     * @since   1.0
     */
    protected function setPageUrlSef($page)
    {
        $url = $this->page_url;

        if ($this->index_in_url === true) {
            $url .= '/index.php';
        }

        $url .= '/start/' . (int)$page;

        if (is_array($this->query_parameters) && count($this->query_parameters) > 0) {
            foreach ($this->query_parameters as $key => $value) {

                $url .= '/'
                    . $this->query_parameters[$key]
                    . '/'
                    . $this->query_parameters[$value];
            }
        }

        return $url;
    }
}
