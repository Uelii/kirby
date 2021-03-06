<?php

namespace Kirby\Toolkit;

use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    public function testDefaultPage()
    {
        $pagination = new Pagination();
        $this->assertEquals(0, $pagination->page());

        $pagination = new Pagination(['total' => 1]);
        $this->assertEquals(1, $pagination->page());
    }

    public function testPageSetter()
    {
        $pagination = new Pagination();
        $this->assertInstanceOf(Pagination::class, $pagination->page(12));
    }

    public function testPageGetter()
    {
        $pagination = new Pagination();
        $pagination->page(12);
        $this->assertEquals(0, $pagination->page());
    }

    public function testTotalDefault()
    {
        $pagination = new Pagination();
        $this->assertEquals(0, $pagination->total());
    }

    public function testTotalSetter()
    {
        $pagination = new Pagination();
        $this->assertInstanceOf(Pagination::class, $pagination->total(12));
    }

    public function testTotalGetter()
    {
        $pagination = new Pagination();
        $pagination->total(12);
        $this->assertEquals(12, $pagination->total());
    }

    public function testTotalInvalid()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Invalid total number of items: -1');

        $pagination = new Pagination();
        $pagination->total(-1);
    }

    public function testLimitDefault()
    {
        $pagination = new Pagination();
        $this->assertEquals(20, $pagination->limit());
    }

    public function testLimitSetter()
    {
        $pagination = new Pagination();
        $this->assertInstanceOf(Pagination::class, $pagination->limit(100));
    }

    public function testLimitGetter()
    {
        $pagination = new Pagination();
        $pagination->limit(100);
        $this->assertEquals(100, $pagination->limit());
    }

    public function testLimitInvalid()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Invalid pagination limit: -1');

        $pagination = new Pagination();
        $pagination->limit(-1);
    }

    public function testStart()
    {
        $pagination = new Pagination([
            'total' => 42
        ]);

        $this->assertEquals(1, $pagination->start());

        // go to the second page
        $pagination->page(2);
        $this->assertEquals(21, $pagination->start());

        // set a different limit
        $pagination->limit(10);
        $this->assertEquals(11, $pagination->start());
    }

    public function testEnd()
    {
        $pagination = new Pagination([
            'total' => 42
        ]);

        $this->assertEquals(20, $pagination->end());

        // go to the second page
        $pagination->page(2);
        $this->assertEquals(40, $pagination->end());

        // set a different limit
        $pagination->limit(10);
        $this->assertEquals(20, $pagination->end());
    }

    public function testEndWithOneItem()
    {
        $pagination = new Pagination([
            'total' => 1
        ]);

        $this->assertEquals(1, $pagination->end());
    }

    public function testPages()
    {
        $pagination = new Pagination();
        $this->assertEquals(0, $pagination->pages());

        $pagination = new Pagination(['total' => 1]);
        $this->assertEquals(1, $pagination->pages());

        $pagination = new Pagination(['total' => 10]);
        $this->assertEquals(1, $pagination->pages());

        $pagination = new Pagination(['total' => 21]);
        $this->assertEquals(2, $pagination->pages());

        $pagination = new Pagination(['total' => 11, 'limit' => 5]);
        $this->assertEquals(3, $pagination->pages());
    }

    public function testFirstPage()
    {
        $pagination = new Pagination();
        $this->assertEquals(0, $pagination->firstPage());

        $pagination = new Pagination(['total' => 1]);
        $this->assertEquals(1, $pagination->firstPage());

        $pagination = new Pagination(['total' => 42]);
        $this->assertEquals(1, $pagination->firstPage());
    }

    public function testLastPage()
    {
        $pagination = new Pagination();
        $this->assertEquals(0, $pagination->lastPage());

        $pagination = new Pagination(['total' => 1]);
        $this->assertEquals(1, $pagination->lastPage());

        $pagination = new Pagination(['total' => 42]);
        $this->assertEquals(3, $pagination->lastPage());
    }

    public function testOffset()
    {
        $pagination = new Pagination();
        $this->assertEquals(0, $pagination->offset());

        $pagination = new Pagination(['total' => 42, 'page' => 2]);
        $this->assertEquals(20, $pagination->offset());

        $pagination = new Pagination(['total' => 42, 'page' => 2, 'limit' => 10]);
        $this->assertEquals(10, $pagination->offset());
    }

    public function testHasPage()
    {
        $pagination = new Pagination([
            'page'  => 1,
            'limit' => 1,
            'total' => 10
        ]);

        $this->assertTrue($pagination->hasPage(1));
        $this->assertTrue($pagination->hasPage(10));

        $this->assertFalse($pagination->hasPage(0));
        $this->assertFalse($pagination->hasPage(11));
    }

    public function testHasPages()
    {
        $pagination = new Pagination();
        $this->assertFalse($pagination->hasPages());

        $pagination = new Pagination(['total' => 1]);
        $this->assertFalse($pagination->hasPages());

        $pagination = new Pagination(['total' => 21]);
        $this->assertTrue($pagination->hasPages());
    }

    public function testHasPrevPage()
    {
        $pagination = new Pagination();
        $this->assertFalse($pagination->hasPrevPage());

        $pagination = new Pagination(['total' => 42, 'page' => 2]);
        $this->assertTrue($pagination->hasPrevPage());
    }

    public function testPrevPage()
    {
        $pagination = new Pagination(['page' => 2, 'total' => 42]);
        $this->assertEquals(1, $pagination->prevPage());

        $pagination = new Pagination(['page' => 1]);
        $this->assertEquals(null, $pagination->prevPage());
    }

    public function testHasNextPage()
    {
        $pagination = new Pagination();
        $this->assertFalse($pagination->hasNextPage());

        $pagination = new Pagination(['total' => 42, 'page' => 5]);
        $this->assertFalse($pagination->hasNextPage());

        $pagination = new Pagination(['total' => 42, 'page' => 2]);
        $this->assertTrue($pagination->hasNextPage());
    }

    public function testNextPage()
    {
        $pagination = new Pagination(['page' => 1, 'total' => 30]);
        $this->assertEquals(2, $pagination->nextPage());

        $pagination = new Pagination(['page' => 3]);
        $this->assertEquals(null, $pagination->nextPage());
    }

    public function testIsFirstPage()
    {
        $pagination = new Pagination();
        $this->assertTrue($pagination->isFirstPage());

        $pagination = new Pagination(['total' => 42]);
        $this->assertTrue($pagination->isFirstPage());

        $pagination = new Pagination(['total' => 42, 'page' => 2]);
        $this->assertFalse($pagination->isFirstPage());
    }

    public function testIsLastPage()
    {
        $pagination = new Pagination();
        $this->assertTrue($pagination->isLastPage());

        $pagination = new Pagination(['total' => 42]);
        $this->assertFalse($pagination->isLastPage());

        $pagination = new Pagination(['total' => 42, 'page' => 3]);
        $this->assertTrue($pagination->isLastPage());
    }

    public function testRange()
    {
        // at the beginning
        $pagination = new Pagination([
            'page'  => 1,
            'total' => 100,
            'limit' => 1
        ]);

        $range = $pagination->range(10);
        $this->assertEquals(range(1, 10), $range);
        $this->assertEquals(1, $pagination->rangeStart(10));
        $this->assertEquals(10, $pagination->rangeEnd(10));

        // in the middle
        $pagination = new Pagination([
            'page'  => 50,
            'total' => 100,
            'limit' => 1
        ]);

        $range = $pagination->range(10);
        $this->assertEquals(range(45, 55), $range);
        $this->assertEquals(45, $pagination->rangeStart(10));
        $this->assertEquals(55, $pagination->rangeEnd(10));

        // at the end
        $pagination = new Pagination([
            'page'  => 100,
            'total' => 100,
            'limit' => 1
        ]);

        $range = $pagination->range(10);
        $this->assertEquals(range(90, 100), $range);
        $this->assertEquals(90, $pagination->rangeStart(10));
        $this->assertEquals(100, $pagination->rangeEnd(10));

        // higher range than pages
        $pagination = new Pagination([
            'page'  => 1,
            'total' => 10,
            'limit' => 1
        ]);

        $range = $pagination->range(12);
        $this->assertEquals(range(1, 10), $range);
        $this->assertEquals(1, $pagination->rangeStart(12));
        $this->assertEquals(10, $pagination->rangeEnd(12));
    }

    public function testToArray()
    {
        $pagination = new Pagination();
        $keys = [
            'page',
            'firstPage',
            'lastPage',
            'pages',
            'offset',
            'limit',
            'total',
            'start',
            'end',
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $pagination->toArray());
        }
    }

    public function testForWithoutArguments()
    {
        $collection = new Collection(['a', 'b', 'c']);
        $pagination = Pagination::for($collection);

        $this->assertEquals(1, $pagination->page());
        $this->assertEquals(1, $pagination->pages());
        $this->assertEquals(20, $pagination->limit());
        $this->assertEquals(3, $pagination->total());
    }

    public function testForWithLimit()
    {
        $collection = new Collection(['a', 'b', 'c']);
        $pagination = Pagination::for($collection, 1);

        $this->assertEquals(1, $pagination->page());
        $this->assertEquals(3, $pagination->pages());
        $this->assertEquals(1, $pagination->limit());
    }

    public function testForWithLimitAndPage()
    {
        $collection = new Collection(['a', 'b', 'c']);
        $pagination = Pagination::for($collection, 1, 2);

        $this->assertEquals(2, $pagination->page());
        $this->assertEquals(3, $pagination->pages());
        $this->assertEquals(1, $pagination->limit());
    }

    public function testForWithOptionsArray()
    {
        $collection = new Collection(['a', 'b', 'c']);
        $pagination = Pagination::for($collection, [
            'limit' => 1,
            'page'  => 2
        ]);

        $this->assertEquals(2, $pagination->page());
        $this->assertEquals(3, $pagination->pages());
        $this->assertEquals(1, $pagination->limit());
    }

    public function testForWithLimitAndOptionsArray()
    {
        $collection = new Collection(['a', 'b', 'c']);
        $pagination = Pagination::for($collection, 1, [
            'page' => 2
        ]);

        $this->assertEquals(2, $pagination->page());
        $this->assertEquals(3, $pagination->pages());
        $this->assertEquals(1, $pagination->limit());
    }
}
