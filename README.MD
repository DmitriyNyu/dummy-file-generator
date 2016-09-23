# DummyFileGenerator

DummyFileGenerator dummy files of arbitrary length and content for testing.

### Installation
Use Composer to install package or download and include files manually.

### Usage
Make sure DummyFileGenerator is installed and available in your namespace.

```sh
$generator = new DummyFileGenerator();
$generator->generateFile($this->__testFilePath, '1234567890', 1024);
```
### Development
This package should not be used in production, but you can use it in your
tests, if for whatever reasons you need to create dummy files and read content
from it.

Feel free to submit a pull request.

License
----
MIT