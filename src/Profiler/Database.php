<?php
/**
 * This class provides profiling functionality for query execution.
 *
 * @implements \Iterator
 * @implements \Countable
 * @implements \Maleficarum\Profiler\Profiler
 */

namespace Maleficarum\Profiler;

class Database implements \Iterator, \Countable, \Maleficarum\Profiler\Profiler
{
    /**
     * Internal storage for profiling data.
     *
     * @var array
     */
    private $data = [];

    /* ------------------------------------ Database methods START ------------------------------------- */
    /**
     * Add a new executed query to this profiler.
     *
     * @param float $start
     * @param float $end
     * @param string $query
     * @param array $params
     *
     * @return \Maleficarum\Profiler\Database
     */
    public function addQuery(float $start, float $end, string $query, array $params = []) : \Maleficarum\Profiler\Database {
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
    /* ------------------------------------ Database methods END --------------------------------------- */

    /* ------------------------------------ Profiler methods START ------------------------------------- */
    /**
     * Get database profiler data.
     * 
     * @see \Maleficarum\Profiler\Profiler::getProfile()
     * @return array
     */
    public function getProfile() {
        return $this->data;
    }
    /* ------------------------------------ Profiler methods END --------------------------------------- */

    /* ------------------------------------ Countable methods START ------------------------------------ */
    /**
     * Count array elements
     *
     * @see \Countable::count()
     * @return int
     */
    public function count() : int {
        return count($this->data);
    }
    /* ------------------------------------ Countable methods END -------------------------------------- */

    /* ------------------------------------ Iterator methods START ------------------------------------- */
    /**
     * Return the current element in an array
     *
     * @see \Iterator::current()
     * @return mixed
     */
    public function current() {
        return current($this->data);
    }

    /**
     * Move forward to next element
     *
     * @see \Iterator::next()
     * @return void
     */
    public function next() {
        next($this->data);
    }

    /**
     * Fetch a key from an array
     *
     * @see \Iterator::key()
     * @return mixed
     */
    public function key() {
        return key($this->data);
    }

    /**
     * Checks if current position is valid
     *
     * @see \Iterator::valid()
     * @return bool
     */
    public function valid() : bool {
        return isset($this->data[$this->key()]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @see \Iterator::rewind()
     * @return void
     */
    public function rewind() {
        reset($this->data);
    }
    /* ------------------------------------ Iterator methods END --------------------------------------- */
}
