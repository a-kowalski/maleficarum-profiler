<?php
/**
 * This interface defines the profiler contract.
 */
declare (strict_types=1);

namespace Maleficarum\Profiler;

interface Profiler {
    
    /* ------------------------------------ Class Methods START ---------------------------------------- */
    
    /**
     * Get profiler data.
     *
     * @return mixed
     */
    public function getProfile();
    
    /* ------------------------------------ Class Methods END ------------------------------------------ */
    
}
