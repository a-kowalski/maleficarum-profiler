<?php
/**
 * This class carries ioc initialization functionality used by this component.
 */
declare (strict_types=1);

namespace Maleficarum\Profiler\Initializer;

class Initializer {
	/**
	 * This will initialize the time profiler.
	 * @return string
	 */
	static public function initializeTime(array $opts = []) : string {
		$time = \Maleficarum\Ioc\Container::get('Maleficarum\Profiler\Time\Generic')->begin((float)$opts['start'] ?? 0);
		
		try {
			$bootstrap = \Maleficarum\Ioc\Container::getDependency('Maleficarum\Bootstrap');
			$bootstrap->setTimeProfiler($time);
		} catch (\RuntimeException $e) {}
		
		\Maleficarum\Ioc\Container::registerDependency('Maleficarum\Profiler\Time', $time);
		return __METHOD__;
	}

	/**
	 * This will initialize the database profiler.
	 * @return string
	 */
	static public function initializeDatabase(array $opts = []) : string {
		$database = \Maleficarum\Ioc\Container::get('Maleficarum\Profiler\Database\Generic');

		try {
			$bootstrap = \Maleficarum\Ioc\Container::getDependency('Maleficarum\Bootstrap');
			$bootstrap->setDbProfiler($database);
		} catch (\RuntimeException $e) {}
		
		\Maleficarum\Ioc\Container::registerDependency('Maleficarum\Profiler\Database', $database);
		return __METHOD__;
	}
}