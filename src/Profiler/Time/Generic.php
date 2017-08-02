<?php
/**
 * This class provides basic time profiling for code execution. It allows for both entire process and milestone analisis.
 */
declare (strict_types=1);

namespace Maleficarum\Profiler\Time;

class Generic implements \Maleficarum\Profiler\Profiler {

    /* ------------------------------------ Class Property START --------------------------------------- */

    /**
     * Definitions for initial and conclusion milestone labels.
     */
    const BEGIN_LABEL = '__BEGIN__';
    const END_LABEL = '__END__';

    /**
     * Internal storage for profiling step times.
     *
     * @var array
     */
    private $data = [];

    /* ------------------------------------ Class Property END ----------------------------------------- */

    /* ------------------------------------ Magic methods START ---------------------------------------- */

    /**
     * Initialize a new time profiler.
     */
    public function __construct() {
        $this->clear();
    }

    /* ------------------------------------ Magic methods END ------------------------------------------ */

    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * Clear any profiling data.
     *
     * @return \Maleficarum\Profiler\Time\Generic
     */
    public function clear(): \Maleficarum\Profiler\Time\Generic {
        $this->data = [];

        return $this;
    }

    /**
     * Add a new profiler milestone.
     *
     * @param string $label
     * @param string|null $comment
     *
     * @return \Maleficarum\Profiler\Time\Generic
     * @throws \RuntimeException
     */
    public function addMilestone(string $label, string $comment = null): \Maleficarum\Profiler\Time\Generic {
        if (count($this->data) < 1) {
            throw new \RuntimeException(sprintf('Cannot add a milestone to a stopped profiler. \%s::addMilestone()', static::class));
        }

        // create a nonempty label if one was not provided
        mb_strlen($label) or $label = uniqid();

        $this->data[$label] = [
            'timestamp' => microtime(true),
            'comment' => $comment
        ];

        return $this;
    }

    /**
     * Fetch an existing milestone.
     *
     * @param string $label
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getMilestone(string $label): array {
        if (!array_key_exists($label, $this->data)) {
            throw new \InvalidArgumentException(sprintf('Invalid milestone label provided. \%s::getMilestone()', static::class));
        }

        return $this->data[$label];
    }

    /**
     * Fetch all milestone labels.
     *
     * @return array
     */
    public function getMilestoneLabels(): array {
        return array_keys($this->data);
    }

    /**
     * Begin time profiling.
     *
     * @param float|null $start
     *
     * @return \Maleficarum\Profiler\Time\Generic
     * @throws \RuntimeException
     */
    public function begin(float $start = null): \Maleficarum\Profiler\Time\Generic {
        if (count($this->data)) {
            throw new \RuntimeException(sprintf('Impossible to activate an already activated time profiler. \%s::begin()', static::class));
        }

        $this->data[self::BEGIN_LABEL] = [
            'timestamp' => is_float($start) ? $start : microtime(true),
            'comment' => '__INITIAL PROFILING TIMESTAMP.'
        ];

        return $this;
    }

    /**
     * Stop time profiling.
     *
     * @return \Maleficarum\Profiler\Time\Generic
     * @throws \RuntimeException
     */
    public function end(): \Maleficarum\Profiler\Time\Generic {
        if (count($this->data) < 1) {
            throw new \RuntimeException(sprintf('Impossible to stop a time profiler that has not been started yet. \%s::end()', static::class));
        }

        if (count($this->data) > 0 && $this->isComplete()) {
            throw new \RuntimeException(sprintf('Impossible to stop an already stopped time profiler. \%s::end()', static::class));
        }

        $this->data[self::END_LABEL] = [
            'timestamp' => microtime(true),
            'comment' => '__FINAL PROFILING TIMESTAMP.'
        ];

        return $this;
    }

    /**
     * Check if this profiler has completed its work.
     *
     * @return bool
     */
    public function isComplete(): bool {
        return array_key_exists(self::END_LABEL, $this->data);
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */

    /* ------------------------------------ Profiler methods START ------------------------------------- */

    /**
     * @see \Maleficarum\Profiler\Profiler::getProfile()
     */
    public function getProfile(string $end = self::END_LABEL, string $start = self::BEGIN_LABEL) {
        if (!array_key_exists(self::BEGIN_LABEL, $this->data)) {
            throw new \RuntimeException(sprintf('Call to getProfile prior to starting the profiling process. \%s::getProfile()', static::class));
        }

        if (!array_key_exists($end, $this->data) || !array_key_exists($start, $this->data)) {
            throw new \InvalidArgumentException(sprintf('Nonexistent profile label provided. \%s::getProfile()', static::class));
        }

        return ($this->data[$end]['timestamp'] - $this->data[$start]['timestamp']);
    }

    /* ------------------------------------ Profiler methods END --------------------------------------- */
}
