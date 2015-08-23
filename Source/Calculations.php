<?php
/**
 * Calculations
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo;

/**
 * Calculations
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class Calculations
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
    protected $properties_array = array();

    /**
     * Set Property Values and Perform Calculations
     *
     * @param   array $options
     *
     * @since   1.0.0
     * @return  array
     */
    public function setValues(array $properties_array, array $options)
    {
        $this->properties_array = $properties_array;

        $this->setClassProperties($options);

        $this->setPaginationDisplayValues();
        $this->setSefUrlIndicators();
        $this->calculateStartAndStopLinks();

        return $this->setReturnOptions();
    }

    /**
     * Set options array with established property values
     *
     * @param   array $options
     *
     * @return  Calculations
     * @since   1.0.0
     */
    protected function setClassProperties($options)
    {
        foreach ($this->properties_array as $key) {
            $this->$key = $options[$key];
        }

        return $this;
    }

    /**
     * Set options array with established property values
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setReturnOptions()
    {
        $options = array();

        foreach ($this->properties_array as $key) {
            $options[$key] = $this->$key;
        }

        return $options;
    }

    /**
     * Set Pagination Display Values
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setPaginationDisplayValues()
    {
        if ((int)$this->display_items_per_page_count === 0) {
            $this->display_items_per_page_count = 9999999;
            $this->start_page_number            = 1;
            $this->display_page_link_count      = 0;
        }

        $this->setStartParameter();

        $this->setDisplayPageLinkCount();

        return $this;
    }

    /**
     * Set Start Parameter
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setStartParameter()
    {
        if ((int)$this->start_page_number > 0) {
        } else {
            $this->start_page_number = 1;
        }

        if (($this->start_page_number * $this->display_items_per_page_count) > $this->total_items) {
            $this->start_page_number = 1;
        }

        return $this;
    }

    /**
     * Set Display Page Link Count
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setDisplayPageLinkCount()
    {
        if ($this->display_page_link_count < 1) {
            $this->display_page_link_count = 5;
        }

        return $this;
    }

    /**
     * Set SEF Url Indicators
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setSefUrlIndicators()
    {
        if ($this->create_sef_url_indicator === true) {
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
     * @since   1.0.0
     * @return  $this
     */
    protected function calculateStartAndStopLinks()
    {
        $this->setPageBoundaries();

        if ($this->start_page_number - 1 > $this->start_links_page_number) {
            $this->start_links_page_number = $this->start_page_number - 1;
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
     * @since   1.0.0
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
     * @since   1.0.0
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
}
