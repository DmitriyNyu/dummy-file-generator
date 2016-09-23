<?php

namespace DummyFileGenerator;

/**
 * Class for creating dummy files of arbitrary length and content for testing
 */
class DummyFileGenerator
{

    /**
     * This is max size of internal chunk (larger chunk will result in less writes)
     * Should not be more than 2/3 of available ram
     * @var int
     */
    private $__internalChunkSize = 1024 * 1024;

    /**
     * This method generates a files of given length by putting $sequence until end of file
     * @todo Add type check and file existing check, make more efficent large chunk generation
     * @param string $path
     * @param string $sequence
     * @param int $length
     */
    public function generateFile($path, $sequence, $length)
    {
        $handler = fopen($path, 'w');
        $largeChunk = $this->__getLargeChunk($sequence, $length);
        $stringLength = mb_strlen($largeChunk);
        $chars = 0;
        while ($chars < $length) {
            if (($length - $chars) < $stringLength) {
                $diff = $length - $chars;
                $largeChunk = mb_substr($largeChunk, 0, $diff);
                $stringLength = mb_strlen($largeChunk);
            }
            $chars += $stringLength;
            fwrite($handler, $largeChunk, $stringLength);
        }
        fclose($handler);
    }

    /**
     * This private method builds large chunk of given $length to reduce input writes
     * @param string $sequence
     * @param int $length
     * @return string
     */
    private function __getLargeChunk($sequence, $length)
    {
        if ($this->__internalChunkSize > $length) {
            return $sequence;
        }
        $largeChunk = '';
        while (mb_strlen($largeChunk) < $this->__internalChunkSize) {
            if ((mb_strlen($largeChunk) * 2) > $this->__internalChunkSize) {
                break;
            }
            $largeChunk .= $sequence;
            $sequence = $largeChunk;
        }
        return $largeChunk;
    }
}
