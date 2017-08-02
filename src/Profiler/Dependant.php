<?php
/**
 * This trait provides functionality common to all classes dependant on the \Maleficarum\Profiler namespace. Unlike most other Dependant traits this one allows for setting and fetching
 * multiple profiler types and instances.
 */
declare (strict_types=1);

namespace Maleficarum\Profiler;

trait Dependant {
    /* ------------------------------------ Class Property START --------------------------------------- */

    /**
     * Internal storage for profiler objects.
     *
     * @var array|\Maleficarum\Profiler\Profiler[]
     */
    protected $profilers = [];

    /* ------------------------------------ Class Property END ----------------------------------------- */

    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * Inject a new profiler provider object.
     *
     * @param \Maleficarum\Profiler\Profiler $profiler
     * @param string $index
     *
     * @return \Maleficarum\Profiler\Dependant
     */
    public function addProfiler(\Maleficarum\Profiler\Profiler $profiler, string $index) {
        if (!mb_strlen($index)) {
            throw new \InvalidArgumentException(sprintf('Incorrect profiler index - empty string provided. \%s::addProfiler()', static::class));
        }

        $this->profilers[$index] = $profiler;

        return $this;
    }

    /**
     * Fetch an assigned profiler object.
     *
     * @param string $index
     *
     * @return \Maleficarum\Profiler\Profiler|null
     */
    public function getProfiler(string $index = 'time'): ?\Maleficarum\Profiler\Profiler {
        if (!array_key_exists($index, $this->profilers)) {
            return null;
        }

        return $this->profilers[$index];
    }

    /**
     * Detach all profiler objects.
     *
     * @return \Maleficarum\Profiler\Dependant
     */
    public function detachProfilers() {
        $this->profilers = [];

        return $this;
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */
}
