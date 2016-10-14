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
     * @var array
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
     * @throws \InvalidArgumentException
     */
    public function addProfiler($profiler, $index) {
        if (!$profiler instanceof \Maleficarum\Profiler\Profiler) {
            throw new \InvalidArgumentException(sprintf('Incorrect profiler - \Maleficarum\Profiler\Profiler expected. \%s::addProfiler()', get_class($this)));
        }

        if (!is_string($index) || !mb_strlen($index)) {
            throw new \InvalidArgumentException(sprintf('Incorrect profiler index - string expected. \%s::addProfiler()', get_class($this)));
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
     * @throws \InvalidArgumentException
     */
    public function getProfiler($index = 'time') {
        if (!is_string($index) || !mb_strlen($index)) {
            throw new \InvalidArgumentException(sprintf('Incorrect profiler index - nonempty string expected. \%s::getProfiler()', get_class($this)));
        }

        if (array_key_exists($index, $this->profilers)) {
            return $this->profilers[$index];
        }

        return null;
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
