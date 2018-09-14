<?php
/**
 * This class carries ioc initialization functionality used by this component.
 */
declare (strict_types=1);

namespace Maleficarum\Profiler\Initializer;

class Initializer {
    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * This will initialize the time profiler.
     *
     * @param array $opts
     *
     * @return string
     */
    static public function initializeTime(array $opts = []): string {
        \Maleficarum\Ioc\Container::registerShare('Maleficarum\Profiler\Time', \Maleficarum\Ioc\Container::get('Maleficarum\Profiler\Time\Generic')->begin((float)$opts['start'] ?? 0));

        return __METHOD__;
    }

    /**
     * This will initialize the database profiler.
     *
     * @param array $opts
     *
     * @return string
     */
    static public function initializeDatabase(array $opts = []): string {
        \Maleficarum\Ioc\Container::registerShare('Maleficarum\Profiler\Database', \Maleficarum\Ioc\Container::get('Maleficarum\Profiler\Database\Generic'));

        return __METHOD__;
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */
}
