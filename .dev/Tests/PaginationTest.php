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
    }

    /**
     * Initialise Event Options
     *
     * @return  $this
     * @since   1.0
     */
    public function testPagination()
    {
        $this->pagination = new Pagination();

        $results = $this->parse->parse();

        $this->assertEquals('page', $results[0]->type);
        $this->assertEquals('xyz', $results[0]->name);
        $this->assertEquals('', $results[0]->wrap);
        $this->assertEquals(array(), $results[0]->attributes);

        return $this;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
}
