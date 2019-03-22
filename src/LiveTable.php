<?php

namespace Keven\CLImate;

use League\CLImate\TerminalObject\Dynamic\DynamicTerminalObject;
use League\CLImate\TerminalObject\Basic\Table;

final class LiveTable extends DynamicTerminalObject
{
    /** @var array[] */
    private $data = [];

    /** @var string[] */
    private $headers = [];

    /** @var int */
    private $lastHeight = 0;

    /** @var int */
    private $lastWidth = 0;

    /**
     * @param array $data
     * @param array $headers
     */
    public function __construct(array $data = [], array $headers = [])
    {
        if (!$headers && $data) {
            $keys = array_keys(key($data));
            if (!is_numeric($keys)) {
                $headers = $keys;
            }
        }

        $this->headers = $headers;
        $this->data = $data;

        $this->buildData();
    }

    /**
     * @param array $data
     * @return Table
     */
    private function createTable(array $data)
    {
        $table = new Table($data);
        $table->parser($this->parser);
        $table->util($this->util);

        return $table;
    }

    private function buildData()
    {
        if (!$this->headers) {
            return;
        }

        $data = $this->data;
        $this->data = [];
        foreach ($data as $index => $line) {
            $this->data[$index] = array_combine($this->headers, $line);
        }
    }

    /**
     * @param array $data
     * @param mixed|null $index
     * @return mixed
     */
    public function add(array $data, $index = null)
    {
        if (null === $index) {
            $this->data[] = $data;
            $index = array_key_last($this->data);
        } else {
            $this->data[$index] = $data;
        }

        $this->buildData();
        $this->draw();

        return $index;
    }

    /** @return void */
    public function remove($index)
    {
        if (!isset($this->data[$index])) {
            return;
        }

        unset($this->data[$index]);

        $this->draw();
    }

    /**
     * @param mixed $index
     * @param array $data
     */
    public function change($index, array $data)
    {
        $this->data[$index] = $data;
        $this->buildData();
        $this->draw();
    }

    /** @return void */
    public function clear()
    {
        $this->data = [];

        if ($this->headers) {
            $this->data = [array_combine($this->headers, array_fill(0, count($this->headers), ''))];
            $this->draw();
        } else {
            $this->clearLastOutput();
        }
   }

   public function delete()
   {
        $this->data = [];
        $this->headers = [];
        $this->clearLastOutput();
   }

   private function clearLastOutput()
   {
       if ($this->lastHeight) {
           for ($n = 0; $n < $this->lastHeight; $n++) {
               $this->output->sameLine()->write(
                   $this->util->cursor->up() .
                   $this->util->cursor->startOfCurrentLine() .
                   $this->util->cursor->deleteCurrentLine()
               );
           }
       }
       $this->lastHeight = 0;
   }

   /** @return void */
    private function draw()
    {
        $this->clearLastOutput();
        $results = $this->createTable($this->data)->result();

        foreach ($results as $result) {
            $this->output->write($this->parser->apply($result));
        }

        $this->lastHeight = count($results);
    }
}

if ( ! function_exists( 'array_key_last' ) ) {
    /**
     * Polyfill for array_key_last() function added in PHP 7.3.
     *
     * Get the last key of the given array without affecting
     * the internal array pointer.
     *
     * @param array $array An array
     *
     * @return mixed The last key of array if the array is not empty; NULL otherwise.
     */
    function array_key_last( $array ) {
        $key = NULL;

        if ( is_array( $array ) ) {

            end( $array );
            $key = key( $array );
        }

        return $key;
    }
}
