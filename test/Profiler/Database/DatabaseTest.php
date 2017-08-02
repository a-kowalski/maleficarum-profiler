<?php
declare(strict_types = 1);

/**
 * Tests for the \Maleficarum\Profiler\Database class.
 */

namespace Maleficarum\Profiler\Tests\Database;

class DatabaseTest extends \PHPUnit\Framework\TestCase
{
    private static $profiler;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$profiler = new \Maleficarum\Profiler\Database\Generic();
    }

    /* ------------------------------------ Method: addQuery START ------------------------------------- */
    /**
     * @dataProvider queryDataProvider
     */
    public function testAddQuery($start, $end, $execution, $query, $parameters, $parsedQuery, $count) {
        $profiler = self::$profiler;
        $profiler->addQuery($start, $end, $query, $parameters);

        $data = $this->getProperty($profiler, 'data');
        $current = end($data);

        $this->assertArrayHasKey('start', $current);
        $this->assertArrayHasKey('end', $current);
        $this->assertArrayHasKey('execution', $current);
        $this->assertArrayHasKey('query', $current);
        $this->assertArrayHasKey('params', $current);
        $this->assertArrayHasKey('parsedQuery', $current);

        $this->assertSame($start, $current['start']);
        $this->assertSame($end, $current['end']);
        $this->assertSame($execution, $current['execution']);
        $this->assertSame($query, $current['query']);
        $this->assertSame($parameters, $current['params']);
        $this->assertSame($parsedQuery, $current['parsedQuery']);
        $this->assertCount($count, $data);
    }

    public function queryDataProvider() {
        return [
            [0.0, 1.0, 1.0, 'SELECT * FROM "foo" WHERE "column_1" = :foo_bar_1', [':foo_bar_1' => 'foo'], 'SELECT * FROM "foo" WHERE "column_1" = \'foo\'', 1],
            [1.1, 3.43, 2.33, 'SELECT * FROM "bar" WHERE "column_1" = :foo_bar_1 AND "column_2" = :foo_bar_2', [':foo_bar_1' => 'foo', ':foo_bar_2' => 'bar'], 'SELECT * FROM "bar" WHERE "column_1" = \'foo\'AND "column_2" = \'bar\'', 2],
        ];
    }
    /* ------------------------------------ Method: addQuery END --------------------------------------- */

    /* ------------------------------------ Helper methods START --------------------------------------- */
    /**
     * Get object property value
     *
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    private function getProperty($object, string $property) {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $value = $reflection->getValue($object);
        $reflection->setAccessible(false);

        return $value;
    }
    /* ------------------------------------ Helper methods END ----------------------------------------- */
}
