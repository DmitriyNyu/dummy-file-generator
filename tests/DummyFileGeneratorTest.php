<?php

namespace DummyFileGenerator\tests;

use PHPUnit\Framework\TestCase;
use DummyFileGenerator\DummyFileGenerator;
use BigFileTools\BigFileTools;
use Exception;

class DummyFileGeneratorTest extends TestCase
{

    /**
     * Directory for temp files
     * @var string
     */
    private $__testFileDir = __DIR__ . DIRECTORY_SEPARATOR . 'temp';

    /**
     * File name of temp file
     * @todo remove code duplication
     * @var string
     */
    private $__testFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'testFile.txt';

    /**
     * Trying to create directory for test file
     * @todo Implement usage of virtual file system for testing class (https://github.com/mikey179/vfsStream-examples)
     * @throws Exception
     */
    public function setUp()
    {
        if (!is_dir($this->__testFileDir)) {
            if (!mkdir($this->__testFileDir)) {
                throw new Exception('Cannot create directory for test files');
            }
        }
        if (!is_writable($this->__testFileDir)) {
            throw new Exception('Directory is not writeable');
        }
    }

    /**
     * Removing both file and test directory
     * @todo Implement usage of virtual file system for testing class
     */
    public function tearDown()
    {
        if (file_exists($this->__testFilePath)) {
            unlink($this->__testFilePath);
        }
        if (is_dir($this->__testFileDir)) {
            rmdir($this->__testFileDir);
        }
    }

    /**
     * Object must be of correct name and namespace
     */
    public function testDummyFileGeneratorCanBeInstantiated()
    {
        $generator = new DummyFileGenerator();
        $this->assertInstanceOf('DummyFileGenerator\DummyFileGenerator', $generator, 'DummyFileGenerator must be an instance of DummyFileGenerator\DummyFileGenerator');
    }

    /**
     * Test that both filesize and content sequence match given arguments
     */
    public function testDummyFileGeneratorMustGenerateFilesOfGivenLengthWithCorrectContent()
    {
        $generator = new DummyFileGenerator();
        $smallByteSize = 15;
        $generator->generateFile($this->__testFilePath, '1234567890', $smallByteSize);
        $this->assertTrue(file_exists($this->__testFilePath));
        $this->assertEquals($smallByteSize, filesize($this->__testFilePath));
        $this->assertEquals('123456789012345', file_get_contents($this->__testFilePath));
    }

    /**
     * Test of class can create 4 gb file. That may take a minute.
     */
    public function testDummyFileGeneratorMustGenerateVeryLargeFilesWithoutFailing()
    {
        $generator = new DummyFileGenerator();
        $largeByteSize = (1073741824 * 4);
        $generator->generateFile($this->__testFilePath, '1234567890', $largeByteSize);
        $file = BigFileTools::createDefault()->getFile($this->__testFilePath);
        $this->assertTrue(file_exists($this->__testFilePath));
        $this->assertEquals($largeByteSize, $file->getSize()->toBase(10));
    }
}
