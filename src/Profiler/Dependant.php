<?php
/**
 * This trait provides functionality common to all classes dependant on the \Maleficarum\Profiler namespace. Unlike most other Dependant traits this one allows for setting and fetching
 * multiple profiler types and instances.
 *
 * @trait
 */

namespace Maleficarum\Profiler;

trait Dependant
{
    /**
     * Internal storage for profiler objects.
     *
     * @var array|\Maleficarum\Profiler\Profiler[]
     */
    protected $profilers = [];

    /* ------------------------------------ Dependant methods START ------------------------------------ */
    /**
     * Inject a new profiler provider object.
     * 
     * @param \Maleficarum\Profiler\Profiler $profiler
     * @param string $index
     *
     * @return $this
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
    public function getProfiler(string $index = 'time') {
        if (!array_key_exists($index, $this->profilers)) {
            return null;
        }

        return $this->profilers[$index];
    }

    /**
     * Detach all profiler objects.
     *
     * @return $this
     */
    public function detachProfilers() {
        $this->profilers = [];

        return $this;
    }
    /* ------------------------------------ Dependant methods END -------------------------------------- */
}
