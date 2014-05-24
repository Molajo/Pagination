<?php
/**
 * Pagination Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Pagination\Test;

use Molajo\Pagination;

/**
 * Pagination Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class PaginationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Object
     */
    protected $pagination;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        $this->pagination = new \Molajo\Pagination();
    }

    /**
     * @covers  Molajo\Pagination::getPaginationData
     * @covers  Molajo\Pagination::calculateValues
     *
     * @covers  Molajo\Calculations::setValues
     * @covers  Molajo\Calculations::setClassProperties
     * @covers  Molajo\Calculations::setReturnOptions
     * @covers  Molajo\Calculations::setPaginationDisplayValues
     * @covers  Molajo\Calculations::setStartParameter
     * @covers  Molajo\Calculations::setDisplayPageLinkCount
     * @covers  Molajo\Calculations::setSefUrlIndicators
     * @covers  Molajo\Calculations::calculateStartAndStopLinks
     * @covers  Molajo\Calculations::setPageBoundaries
     * @covers  Molajo\Calculations::adjustBoundariesForContent
     *
     * @covers  Molajo\Pagination::createPaginationRow
     * @covers  Molajo\Pagination::getPageUrlDriver
     * @covers  Molajo\Pagination::getPageUrl
     * @covers  Molajo\Pagination::adjustPageNumberForContent
     * @covers  Molajo\Pagination::getFirstPage
     * @covers  Molajo\Pagination::getPreviousPage
     * @covers  Molajo\Pagination::getCurrentPage
     * @covers  Molajo\Pagination::getNextPage
     * @covers  Molajo\Pagination::getLastPage
     * @covers  Molajo\Pagination::getStartLinksPage
     * @covers  Molajo\Pagination::getStopLinksPage
     * @covers  Molajo\Pagination::getTotalItems
     * @covers  Molajo\Pagination::setPageUrlParameters
     * @covers  Molajo\Pagination::setPageUrlSef
     * @covers  Molajo\Pagination::setPageUrl
     *
     * @return  $this
     * @since   1.0
     */
    public function testPagination1()
    {
        $display_items_per_page_count   = 3;
        $display_page_link_count        = 3;
        $create_sef_url_indicator       = true;
        $display_index_in_url_indicator = true;
        $total_items                    = 30;
        $page_url                       = 'http://example.com';
        $parameter_start                = 1;
        $other_query_parameters         = array('dog' => 'woof', 'cat' => 'meow');

        $row           = $this->pagination->getPaginationData(
            $total_items,
            $page_url,
            $parameter_start,
            $other_query_parameters,
            $display_items_per_page_count,
            $display_page_link_count,
            $create_sef_url_indicator,
            $display_index_in_url_indicator
        );

        $page_links    = array();
        $page_links[1] = 'http://example.com/index.php/start/1/dog/woof/cat/meow';
        $page_links[2] = 'http://example.com/index.php/start/2/dog/woof/cat/meow';
        $page_links[3] = 'http://example.com/index.php/start/3/dog/woof/cat/meow';

        $this->assertEquals(1, $row->first_page_number);
        $this->assertEquals('http://example.com/index.php/start/1/dog/woof/cat/meow', $row->first_page_link);
        $this->assertEquals(1, $row->previous_page_number);
        $this->assertEquals('http://example.com/index.php/start/1/dog/woof/cat/meow', $row->previous_page_link);
        $this->assertEquals(2, $row->next_page_number);
        $this->assertEquals('http://example.com/index.php/start/2/dog/woof/cat/meow', $row->next_page_link);
        $this->assertEquals(10, $row->last_page_number);
        $this->assertEquals('http://example.com/index.php/start/10/dog/woof/cat/meow', $row->last_page_link);
        $this->assertEquals(1, $row->current_page_number);
        $this->assertEquals(
            'http://example.com/index.php/start/1/dog/woof/cat/meow',
            $row->current_page_link
        );
        $this->assertEquals(1, $row->start_links_page_number);
        $this->assertEquals(3, $row->stop_links_page_number);
        $this->assertEquals($page_links, $row->page_links_array);
        $this->assertEquals(30, $row->total_items);

        return $this;
    }

    /**
     * @covers  Molajo\Pagination::getPaginationData
     * @covers  Molajo\Pagination::calculateValues
     *
     * @covers  Molajo\Calculations::setValues
     * @covers  Molajo\Calculations::setClassProperties
     * @covers  Molajo\Calculations::setReturnOptions
     * @covers  Molajo\Calculations::setPaginationDisplayValues
     * @covers  Molajo\Calculations::setStartParameter
     * @covers  Molajo\Calculations::setDisplayPageLinkCount
     * @covers  Molajo\Calculations::setSefUrlIndicators
     * @covers  Molajo\Calculations::calculateStartAndStopLinks
     * @covers  Molajo\Calculations::setPageBoundaries
     * @covers  Molajo\Calculations::adjustBoundariesForContent
     *
     * @covers  Molajo\Pagination::createPaginationRow
     * @covers  Molajo\Pagination::getPageUrlDriver
     * @covers  Molajo\Pagination::getPageUrl
     * @covers  Molajo\Pagination::adjustPageNumberForContent
     * @covers  Molajo\Pagination::getFirstPage
     * @covers  Molajo\Pagination::getPreviousPage
     * @covers  Molajo\Pagination::getCurrentPage
     * @covers  Molajo\Pagination::getNextPage
     * @covers  Molajo\Pagination::getLastPage
     * @covers  Molajo\Pagination::getStartLinksPage
     * @covers  Molajo\Pagination::getStopLinksPage
     * @covers  Molajo\Pagination::getTotalItems
     * @covers  Molajo\Pagination::setPageUrlParameters
     * @covers  Molajo\Pagination::setPageUrlSef
     * @covers  Molajo\Pagination::setPageUrl
     *
     * @return  $this
     * @since   1.0
     */
    public function testPagination2()
    {
        $display_items_per_page_count   = 3;
        $display_page_link_count        = 3;
        $create_sef_url_indicator       = false;
        $display_index_in_url_indicator = false;
        $total_items                    = 30;
        $page_url                       = 'http://example.com';
        $parameter_start                = 5;
        $other_query_parameters         = array('dog' => 'woof', 'cat' => 'meow');

        $row           = $this->pagination->getPaginationData(
            $total_items,
            $page_url,
            $parameter_start,
            $other_query_parameters,
            $display_items_per_page_count,
            $display_page_link_count,
            $create_sef_url_indicator,
            $display_index_in_url_indicator
        );
        $page_links    = array();
        $page_links[4] = 'http://example.com/index.php?start=4&dog=woof&cat=meow';
        $page_links[5] = 'http://example.com/index.php?start=5&dog=woof&cat=meow';
        $page_links[6] = 'http://example.com/index.php?start=6&dog=woof&cat=meow';

        $this->assertEquals(1, $row->first_page_number);
        $this->assertEquals('http://example.com/index.php?start=1&dog=woof&cat=meow', $row->first_page_link);
        $this->assertEquals(4, $row->previous_page_number);
        $this->assertEquals('http://example.com/index.php?start=4&dog=woof&cat=meow', $row->previous_page_link);
        $this->assertEquals(6, $row->next_page_number);
        $this->assertEquals('http://example.com/index.php?start=6&dog=woof&cat=meow', $row->next_page_link);
        $this->assertEquals(10, $row->last_page_number);
        $this->assertEquals('http://example.com/index.php?start=10&dog=woof&cat=meow', $row->last_page_link);
        $this->assertEquals(5, $row->current_page_number);
        $this->assertEquals(
            'http://example.com/index.php?start=5&dog=woof&cat=meow',
            $row->current_page_link
        );
        $this->assertEquals(4, $row->start_links_page_number);
        $this->assertEquals(6, $row->stop_links_page_number);
        $this->assertEquals($page_links, $row->page_links_array);
        $this->assertEquals(30, $row->total_items);

        return $this;
    }

    /**
     * @covers  Molajo\Pagination::getPaginationData
     * @covers  Molajo\Pagination::calculateValues
     *
     * @covers  Molajo\Calculations::setValues
     * @covers  Molajo\Calculations::setClassProperties
     * @covers  Molajo\Calculations::setReturnOptions
     * @covers  Molajo\Calculations::setPaginationDisplayValues
     * @covers  Molajo\Calculations::setStartParameter
     * @covers  Molajo\Calculations::setDisplayPageLinkCount
     * @covers  Molajo\Calculations::setSefUrlIndicators
     * @covers  Molajo\Calculations::calculateStartAndStopLinks
     * @covers  Molajo\Calculations::setPageBoundaries
     * @covers  Molajo\Calculations::adjustBoundariesForContent
     *
     * @covers  Molajo\Pagination::createPaginationRow
     * @covers  Molajo\Pagination::getPageUrlDriver
     * @covers  Molajo\Pagination::getPageUrl
     * @covers  Molajo\Pagination::adjustPageNumberForContent
     * @covers  Molajo\Pagination::getFirstPage
     * @covers  Molajo\Pagination::getPreviousPage
     * @covers  Molajo\Pagination::getCurrentPage
     * @covers  Molajo\Pagination::getNextPage
     * @covers  Molajo\Pagination::getLastPage
     * @covers  Molajo\Pagination::getStartLinksPage
     * @covers  Molajo\Pagination::getStopLinksPage
     * @covers  Molajo\Pagination::getTotalItems
     * @covers  Molajo\Pagination::setPageUrlParameters
     * @covers  Molajo\Pagination::setPageUrlSef
     * @covers  Molajo\Pagination::setPageUrl
     *
     * @return  $this
     * @since   1.0
     */
    public function testPaginationEnd()
    {
        $display_items_per_page_count   = 3;
        $display_page_link_count        = 3;
        $create_sef_url_indicator       = false;
        $display_index_in_url_indicator = false;
        $total_items                    = 30;
        $page_url                       = 'http://example.com';
        $parameter_start                = 10;
        $other_query_parameters         = array('dog' => 'woof', 'cat' => 'meow');

        $row = $this->pagination->getPaginationData(
            $total_items,
            $page_url,
            $parameter_start,
            $other_query_parameters,
            $display_items_per_page_count,
            $display_page_link_count,
            $create_sef_url_indicator,
            $display_index_in_url_indicator
        );

        $page_links     = array();
        $page_links[8]  = 'http://example.com/index.php?start=8&dog=woof&cat=meow';
        $page_links[9]  = 'http://example.com/index.php?start=9&dog=woof&cat=meow';
        $page_links[10] = 'http://example.com/index.php?start=10&dog=woof&cat=meow';

        $this->assertEquals(1, $row->first_page_number);
        $this->assertEquals('http://example.com/index.php?start=1&dog=woof&cat=meow', $row->first_page_link);
        $this->assertEquals(9, $row->previous_page_number);
        $this->assertEquals('http://example.com/index.php?start=9&dog=woof&cat=meow', $row->previous_page_link);
        $this->assertEquals(10, $row->next_page_number);
        $this->assertEquals('http://example.com/index.php?start=10&dog=woof&cat=meow', $row->next_page_link);
        $this->assertEquals(10, $row->last_page_number);
        $this->assertEquals('http://example.com/index.php?start=10&dog=woof&cat=meow', $row->last_page_link);
        $this->assertEquals(10, $row->current_page_number);
        $this->assertEquals(
            'http://example.com/index.php?start=10&dog=woof&cat=meow',
            $row->current_page_link
        );
        $this->assertEquals(8, $row->start_links_page_number);
        $this->assertEquals(10, $row->stop_links_page_number);
        $this->assertEquals($page_links, $row->page_links_array);
        $this->assertEquals(30, $row->total_items);

        return $this;
    }

    /**
     * @covers  Molajo\Pagination::getPaginationData
     * @covers  Molajo\Pagination::calculateValues
     *
     * @covers  Molajo\Calculations::setValues
     * @covers  Molajo\Calculations::setClassProperties
     * @covers  Molajo\Calculations::setReturnOptions
     * @covers  Molajo\Calculations::setPaginationDisplayValues
     * @covers  Molajo\Calculations::setStartParameter
     * @covers  Molajo\Calculations::setDisplayPageLinkCount
     * @covers  Molajo\Calculations::setSefUrlIndicators
     * @covers  Molajo\Calculations::calculateStartAndStopLinks
     * @covers  Molajo\Calculations::setPageBoundaries
     * @covers  Molajo\Calculations::adjustBoundariesForContent
     *
     * @covers  Molajo\Pagination::createPaginationRow
     * @covers  Molajo\Pagination::getPageUrlDriver
     * @covers  Molajo\Pagination::getPageUrl
     * @covers  Molajo\Pagination::adjustPageNumberForContent
     * @covers  Molajo\Pagination::getFirstPage
     * @covers  Molajo\Pagination::getPreviousPage
     * @covers  Molajo\Pagination::getCurrentPage
     * @covers  Molajo\Pagination::getNextPage
     * @covers  Molajo\Pagination::getLastPage
     * @covers  Molajo\Pagination::getStartLinksPage
     * @covers  Molajo\Pagination::getStopLinksPage
     * @covers  Molajo\Pagination::getTotalItems
     * @covers  Molajo\Pagination::setPageUrlParameters
     * @covers  Molajo\Pagination::setPageUrlSef
     * @covers  Molajo\Pagination::setPageUrl
     *
     * @return  $this
     * @since   1.0
     */
    public function testPagination999()
    {
        $display_items_per_page_count   = 3;
        $display_page_link_count        = 3;
        $create_sef_url_indicator       = true;
        $display_index_in_url_indicator = true;
        $total_items                    = 30;
        $page_url                       = 'http://example.com';
        $parameter_start                = 999999999999999999999999999;
        $other_query_parameters         = array('dog' => 'woof', 'cat' => 'meow');

        $row           = $this->pagination->getPaginationData(
            $total_items,
            $page_url,
            $parameter_start,
            $other_query_parameters,
            $display_items_per_page_count,
            $display_page_link_count,
            $create_sef_url_indicator,
            $display_index_in_url_indicator
        );
        $page_links    = array();
        $page_links[1] = 'http://example.com/index.php/start/1/dog/woof/cat/meow';
        $page_links[2] = 'http://example.com/index.php/start/2/dog/woof/cat/meow';
        $page_links[3] = 'http://example.com/index.php/start/3/dog/woof/cat/meow';

        $this->assertEquals(1, $row->first_page_number);
        $this->assertEquals('http://example.com/index.php/start/1/dog/woof/cat/meow', $row->first_page_link);
        $this->assertEquals(1, $row->previous_page_number);
        $this->assertEquals('http://example.com/index.php/start/1/dog/woof/cat/meow', $row->previous_page_link);
        $this->assertEquals(2, $row->next_page_number);
        $this->assertEquals('http://example.com/index.php/start/2/dog/woof/cat/meow', $row->next_page_link);
        $this->assertEquals(10, $row->last_page_number);
        $this->assertEquals('http://example.com/index.php/start/10/dog/woof/cat/meow', $row->last_page_link);
        $this->assertEquals(1, $row->current_page_number);
        $this->assertEquals(
            'http://example.com/index.php/start/1/dog/woof/cat/meow',
            $row->current_page_link
        );
        $this->assertEquals(1, $row->start_links_page_number);
        $this->assertEquals(3, $row->stop_links_page_number);
        $this->assertEquals($page_links, $row->page_links_array);
        $this->assertEquals(30, $row->total_items);

        return $this;
    }

    /**
     * @covers  Molajo\Pagination::getPaginationData
     * @covers  Molajo\Pagination::calculateValues
     *
     * @covers  Molajo\Calculations::setValues
     * @covers  Molajo\Calculations::setClassProperties
     * @covers  Molajo\Calculations::setReturnOptions
     * @covers  Molajo\Calculations::setPaginationDisplayValues
     * @covers  Molajo\Calculations::setStartParameter
     * @covers  Molajo\Calculations::setDisplayPageLinkCount
     * @covers  Molajo\Calculations::setSefUrlIndicators
     * @covers  Molajo\Calculations::calculateStartAndStopLinks
     * @covers  Molajo\Calculations::setPageBoundaries
     * @covers  Molajo\Calculations::adjustBoundariesForContent
     *
     * @covers  Molajo\Pagination::createPaginationRow
     * @covers  Molajo\Pagination::getPageUrlDriver
     * @covers  Molajo\Pagination::getPageUrl
     * @covers  Molajo\Pagination::adjustPageNumberForContent
     * @covers  Molajo\Pagination::getFirstPage
     * @covers  Molajo\Pagination::getPreviousPage
     * @covers  Molajo\Pagination::getCurrentPage
     * @covers  Molajo\Pagination::getNextPage
     * @covers  Molajo\Pagination::getLastPage
     * @covers  Molajo\Pagination::getStartLinksPage
     * @covers  Molajo\Pagination::getStopLinksPage
     * @covers  Molajo\Pagination::getTotalItems
     * @covers  Molajo\Pagination::setPageUrlParameters
     * @covers  Molajo\Pagination::setPageUrlSef
     * @covers  Molajo\Pagination::setPageUrl
     *
     * @return  $this
     * @since   1.0
     */
    public function testPaginationEmpty()
    {
        $display_items_per_page_count   = 3;
        $display_page_link_count        = 3;
        $create_sef_url_indicator       = true;
        $display_index_in_url_indicator = true;
        $total_items                    = 0;
        $page_url                       = 'http://example.com';
        $parameter_start                = 3;
        $other_query_parameters         = array('dog' => 'woof', 'cat' => 'meow');

        $row = $this->pagination->getPaginationData(
            $total_items,
            $page_url,
            $parameter_start,
            $other_query_parameters,
            $display_items_per_page_count,
            $display_page_link_count,
            $create_sef_url_indicator,
            $display_index_in_url_indicator
        );

        $this->assertEquals(null, $row);

        return $this;
    }
}
