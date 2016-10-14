<?php
/**
 * This interface defines the profiler contract.
 *
 * @interface
 */

namespace Maleficarum\Profiler;

interface Profiler
{
    /* ------------------------------------ Profiler methods START ------------------------------------- */
    /**
     * Get profiler data.
     *
     * @return float
     */
    public function getProfile();
    /* ------------------------------------ Profiler methods END --------------------------------------- */
}
