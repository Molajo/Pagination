<?php
/**
 * Mock Data for Sample Pagination Theme
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Pagination;

use stdClass;

/**
 * Mock Data for Sample Pagination Theme
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class MockData
{
    /**
     * Data
     *
     * @var    array
     * @since  1.0
     */
    protected $data = array();

    /**
     * Start
     *
     * @var    int
     * @since  1.0
     */
    protected $start = 1;

    /**
     * Items per page
     *
     * @var    int
     * @since  1.0
     */
    protected $display_items_per_page_count = 10;

    /**
     * Number of page links to show
     *
     * @var    int
     * @since  1.0
     */
    protected $display_page_link_count = 5;

    /**
     * Class Constructor
     *
     * @param  int $start
     *
     * @since  1.0
     */
    public function __construct(
        $start = 1,
        $display_items_per_page_count = 3
    ) {
        if ((int)$start < 1) {
            $this->start = 1;
        } else {
            $this->start = (int)$start;
        }

        $this->display_items_per_page_count = (int)$display_items_per_page_count;

        $this->createMockData();
    }

    /**
     * Instantiate Class
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getData()
    {
        $i                 = 0;
        $count_return_data = 0;
        $return_data       = array();

        foreach ($this->data as $data) {

            if ($i < ($this->start * $this->display_items_per_page_count) - $this->display_items_per_page_count) {
                // skip for previous pages

            } elseif ($count_return_data < $this->display_items_per_page_count) {
                $count_return_data ++;
                $return_data[] = $data;
            }

            $i ++;
        }

        return $return_data;
    }

    /**
     * Total rows
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getTotalItemsCount()
    {
        return count($this->data);
    }

    /**
     * Create Mock Data
     *
     * @return  array
     * @since   1.0
     */
    public function createMockData()
    {
        $this->data = array();

        $row           = new stdClass();
        $row->title    = 'List Item 1';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 2';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 3';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 4';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 5';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 6';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 7';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 8';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 9';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 10';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 11';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 12';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 13';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 14';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 15';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 16';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 17';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 18';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 19';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 20';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 21';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 22';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 23';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 24';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 25';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 26';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 27';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 28';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 29';
        $this->data [] = $row;

        $row           = new stdClass();
        $row->title    = 'List Item 30';
        $this->data [] = $row;
        return $this;
    }
}

