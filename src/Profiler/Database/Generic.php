<?php
/**
 * This class provides profiling functionality for query execution.
 */
declare (strict_types=1);

namespace Maleficarum\Profiler\Database;

class Generic implements \Iterator, \Countable, \Maleficarum\Profiler\Profiler {
    
    /* ------------------------------------ Class Property START --------------------------------------- */
    
    /**
     * Internal storage for profiling data.
     *
     * @var array
     */
    private $data = [];

    /* ------------------------------------ Class Property END ----------------------------------------- */

    /* ------------------------------------ Class Methods START ---------------------------------------- */
    
    /**
     * Add a new executed query to this profiler.
     *
     * @param float $start
     * @param float $end
     * @param string $query
     * @param array $params
     * @return \Maleficarum\Profiler\Database\Generic
     */
    public function addQuery(float $start, float $end, string $query, array $params = []) : \Maleficarum\Profiler\Database\Generic {
        $entry = [
            'start' => $start,
            'end' => $end,
            'execution' => $end - $start,
            'query' => $query,
            'params' => $params,
            'parsedQuery' => $query
        ];

        $patterns = [];
        $values = [];
        foreach ($params as $key => $value) {
            $patterns[] = '/' . $key . '([^a-zA-Z\d_]){0,1}/';
            $values[] = "'$value'";
        }

        $entry['parsedQuery'] = preg_replace($patterns, $values, $entry['parsedQuery']);

        $this->data[] = $entry;

        return $this;
    }
    
    /* ------------------------------------ Class Methods END ------------------------------------------ */

    /* ------------------------------------ Profiler methods START ------------------------------------- */
    
    /**
     * @see \Maleficarum\Profiler\Profiler::getProfile()
     */
    public function getProfile() {
        return $this->data;
    }
    
    /* ------------------------------------ Profiler methods END --------------------------------------- */

    /* ------------------------------------ Countable methods START ------------------------------------ */
    
    /**
     * @see \Countable::count()
     */
    public function count() : int {
        return count($this->data);
    }
    
    /* ------------------------------------ Countable methods END -------------------------------------- */

    /* ------------------------------------ Iterator methods START ------------------------------------- */
    
    /**
     * @see \Iterator::current()
     */
    public function current() {
        return current($this->data);
    }

    /**
     * @see \Iterator::next()
     */
    public function next() {
        next($this->data);
    }

    /**
     * @see \Iterator::key()
     */
    public function key() {
        return key($this->data);
    }

    /**
     * @see \Iterator::valid()
     */
    public function valid() : bool {
        return isset($this->data[$this->key()]);
    }

    /**
     * @see \Iterator::rewind()
     */
    public function rewind() {
        reset($this->data);
    }
    
    /* ------------------------------------ Iterator methods END --------------------------------------- */
}
