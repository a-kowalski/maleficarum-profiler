<?php
declare(strict_types = 1);

/**
 * Tests for the \Maleficarum\Profiler\Time class.
 */

namespace Maleficarum\Profiler\Tests;

class TimeTest extends \PHPUnit\Framework\TestCase
{
    private static $dataMock = ['foo' => 'bar'];

    /* ------------------------------------ Method: clear START ---------------------------------------- */
    public function testClear() {
        $profiler = new \Maleficarum\Profiler\Time();

        $this->setProperty($profiler, 'data', self::$dataMock);
        $profiler->clear();
        $data = $this->getProperty($profiler, 'data');

        $this->assertEmpty($data);
    }
    /* ------------------------------------ Method: clear END ------------------------------------------ */

    /* ------------------------------------ Method: addMilestone START --------------------------------- */
    /**
     * @expectedException \RuntimeException
     */
    public function testAddMilestoneNotRunning() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->addMilestone(uniqid());
    }

    public function testAddMilestoneCorrect() {
        $profiler = new \Maleficarum\Profiler\Time();

        $this->setProperty($profiler, 'data', self::$dataMock);

        $label = uniqid();
        $profiler->addMilestone($label);

        $data = $this->getProperty($profiler, 'data');

        $this->assertArrayHasKey($label, $data);
        $this->assertInternalType('array', $data[$label]);
        $this->assertArrayHasKey('timestamp', $data[$label]);
        $this->assertArrayHasKey('comment', $data[$label]);
    }
    /* ------------------------------------ Method: addMilestone END ----------------------------------- */

    /* ------------------------------------ Method: getMilestone START --------------------------------- */
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetMilestoneMissing() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->getMilestone(uniqid());
    }

    public function testGetMilestoneCorrect() {
        $profiler = new \Maleficarum\Profiler\Time();
        $this->setProperty($profiler, 'data', ['foo' => ['bar' => 'baz']]);

        $milestone = $profiler->getMilestone('foo');
        $this->assertSame(['bar' => 'baz'], $milestone);
    }
    /* ------------------------------------ Method: getMilestone END ----------------------------------- */

    /* ------------------------------------ Method: getMilestoneLabels START --------------------------- */
    public function testGetMilestoneLabelsCorrect() {
        $profiler = new \Maleficarum\Profiler\Time();
        $this->setProperty($profiler, 'data', self::$dataMock);

        $labels = $profiler->getMilestoneLabels();

        $this->assertContains('foo', $labels);
    }
    /* ------------------------------------ Method: getMilestoneLabels END ----------------------------- */

    /* ------------------------------------ Method: begin START ---------------------------------------- */
    /**
     * @expectedException \RuntimeException
     */
    public function testBeginIncorrect() {
        $profiler = new \Maleficarum\Profiler\Time();
        $this->setProperty($profiler, 'data', self::$dataMock);

        $profiler->begin();
    }

    public function testBeginCorrectWithoutTimestamp() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->begin();

        $data = $this->getProperty($profiler, 'data');

        $this->assertArrayHasKey('__BEGIN__', $data);
    }

    public function testBeginCorrectWithTimestamp() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->begin(1.0);

        $data = $this->getProperty($profiler, 'data');
        $milestone = $data['__BEGIN__'];

        $this->assertArrayHasKey('timestamp', $milestone);
        $this->assertArrayHasKey('comment', $milestone);
        $this->assertSame(1.0, $milestone['timestamp']);
    }
    /* ------------------------------------ Method: begin END ------------------------------------------ */

    /* ------------------------------------ Method: end START ------------------------------------------ */
    /**
     * @expectedException \RuntimeException
     */
    public function testEndNotStarted() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->end();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEndAlreadyEnded() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->end();
        $profiler->end();
    }

    public function testEndCorrect() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->begin();
        $profiler->end();

        $data = $this->getProperty($profiler, 'data');
        $milestone = current($data);

        $this->assertArrayHasKey('__END__', $data);
        $this->assertArrayHasKey('timestamp', $milestone);
        $this->assertArrayHasKey('comment', $milestone);
    }
    /* ------------------------------------ Method: end END -------------------------------------------- */

    /* ------------------------------------ Method: isComplete START ----------------------------------- */
    public function testIsCompleteNotStarted() {
        $profiler = new \Maleficarum\Profiler\Time();

        $this->assertFalse($profiler->isComplete());
    }

    public function testIsCompleteInProgress() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->begin();

        $this->assertFalse($profiler->isComplete());
    }

    public function testIsCompleteConcluded() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->begin();
        $profiler->end();

        $this->assertTrue($profiler->isComplete());
    }
    /* ------------------------------------ Method: isComplete END ------------------------------------- */

    /* ------------------------------------ Method: getProfile START ----------------------------------- */
    /**
     * @expectedException \RuntimeException
     */
    public function testGetProfileNotRunning() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->getProfile(uniqid(), uniqid());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetNonexistentProfile() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->begin();
        $profiler->getProfile(uniqid(), uniqid());
    }

    public function testGetProfileCorrect() {
        $profiler = new \Maleficarum\Profiler\Time();
        $profiler->begin();
        $profiler->end();

        $this->assertInternalType('float', $profiler->getProfile());
    }
    /* ------------------------------------ Method: getProfile END ------------------------------------- */

    /* ------------------------------------ Helper methods START --------------------------------------- */
    /**
     * Set object property value
     * 
     * @param object $object
     * @param string $property
     * @param mixed $value
     */
    private function setProperty($object, string $property, $value) {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
        $reflection->setAccessible(false);
    }

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
