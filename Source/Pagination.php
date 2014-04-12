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
 * << < 1 2 3 > >>
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Pagination implements PaginationInterface
{
    /**
     * Base URL
     *
     * @var    int
     * @since  1.0
     */
    protected $base_url;

    /**
     * URL Filters
     *
     * @var    array
     * @since  1.0
     */
    protected $query_parameters = array();

    /**
     * Total Items
     * -> ALL, includes previous, current, next
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
    protected $items_per_page = 10;

    /**
     * Number of page links to show
     *
     * @var    int
     * @since  1.0
     */
    protected $display_links = 5;

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
     * Last Page
     *
     * @var    int
     * @since  1.0
     */
    protected $last_page;

    /**
     * Current Page
     *
     * @var    int
     * @since  1.0
     */
    protected $current_page = 0;

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
     * Set pagination values
     *
     * @param  string  $base_url         Base URL for paginated page
     * @param  array   $query_parameters URL Query Parameters (other than start)
     * @param  int     $total_items      Total items in full resultset for data
     * @param  int     $items_per_page   Number of items per page
     * @param  int     $display_links    Number of page number "links" to show
     * @param  int     $current_page     Current page
     * @param  boolean $sef_url          Use SEF URLs?
     * @param  boolean $index_in_url     Use index.php value in URL?
     *
     * @since  1.0
     */
    public function getPaginationData(
        $base_url,
        array $query_parameters = array(),
        $total_items,
        $items_per_page,
        $display_links,
        $current_page,
        $sef_url = false,
        $index_in_url = false
    ) {
        $display_links = 9999;

        $this->base_url         = $base_url;
        $this->query_parameters = $query_parameters;
        $this->total_items      = $total_items;

        if ((int)$items_per_page === 0) {
            $items_per_page = 9999999;
            $current_page   = 1;
        }

        $this->items_per_page = $items_per_page;

        if ((int)$current_page > 0) {
        } else {
            $current_page = 1;
        }

        if (($current_page * $items_per_page) > $this->total_items) {
            $current_page = 1;
        }

        $this->current_page = $current_page;
        $this->last_page    = ceil($this->total_items / $items_per_page);

        if ($display_links < $this->last_page - $this->current_page + 1) {
            $this->display_links = $display_links;
        } else {
            $this->display_links = $this->last_page - $this->current_page + 1;
        }

        $this->start_links_page_number = 1;
        $this->stop_links_page_number  = $this->last_page;
        $temp                          = ceil($this->last_page / $this->display_links);

        for ($i = 1; $i < $temp + 1; $i ++) {
            if (($i * $this->display_links) + 1 >= $current_page
                && $current_page >= ($i * $this->display_links) - $this->display_links + 1
            ) {
                $this->start_links_page_number = ($i * $this->display_links) - $this->display_links + 1;
                $this->stop_links_page_number  = ($i * $this->display_links);

                break;
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

        $row                          = new stdClass();

        $row->first_page_number       = $this->getFirstPage();
        $row->first_page_link         = $this->getPageUrl('first');
        $row->previous_page_number    = $this->getPrevPage();
        $row->previous_page_link      = $this->getPageUrl('previous');
        $row->current_page_number     = $this->getCurrentPage();
        $row->current_page_link       = $this->getPageUrl('current');
        $row->next_page_number        = $this->getNextPage();
        $row->next_page_link          = $this->getPageUrl('next');
        $row->last_page_number        = $this->getLastPage();
        $row->last_page_link          = $this->getPageUrl('last');
        $row->total_items             = $this->getTotalItems();
        $row->start_links_page_number = $this->getStartLinksPage();
        $row->stop_links_page_number  = $this->getStopLinksPage();

        $row->page_links_array = array();
        for ($i = $row->start_links_page_number;
             $i < $row->stop_links_page_number + 1;
             $i ++) {
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
        if ((1 > (int)$this->current_page - 1)) {
            return (int)$this->current_page;
        }

        return (int)$this->current_page - 1;
    }

    /**
     * Get the current page number
     *
     * @return  int
     * @since   1.0
     */
    protected function getCurrentPage()
    {
        return (int)$this->current_page;
    }

    /**
     * Get the page number following the last displayed page number link
     *
     * @return  int
     * @since   1.0
     */
    protected function getNextPage()
    {
        if ((int)$this->current_page + 1 > (int)$this->last_page) {
            return (int)$this->last_page;
        }

        return (int)$this->current_page + 1;
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

        if ($this->sef_url === true) {
            return $this->setPageUrlSef($page_number);
        }

        return $this->setPageUrlParameters($page_number);
    }

    /**
     * Set URL and Parameters for specified key
     *
     * @param   int $page_number
     *
     * @return  string
     * @since   1.0
     */
    protected function setPageUrlParameters($page_number)
    {
        $url = $this->base_url;

        if ($this->index_in_url === true) {
            $url .= '/index.php';
        }

        $url .= '?start=' . (int)$page_number;

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
     * @param   mixed $page_number
     *
     * @return  string
     * @since   1.0
     */
    protected function setPageUrlSef($page_number)
    {
        $url = $this->base_url;

        if ($this->index_in_url === true) {
            $url .= '/index.php';
        }

        $url .= '/start/' . (int)$page_number;

        if (is_array($this->query_parameters) && count($this->query_parameters) > 0) {
            foreach ($this->query_parameters as $key => $value) {
                $url .= '/' . $key . '/' . $value;
            }
        }

        return $url;
    }
}
