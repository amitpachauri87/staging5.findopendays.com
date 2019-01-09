<?php
namespace QiblaImporter;

/**
 * Auto Loader
 *
 * @link    http://www.php-fig.org/psr/psr-4/
 * @link    https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 *
 * @since   1.0.0
 * @package QiblaImporter
 *
 * Given a foo-bar package of classes in the file system at the following
 * paths ...
 *
 *     /path/to/packages/foo-bar/
 *         src/
 *             Baz.php             # Foo\Bar\Baz
 *             Qux/
 *                 Quux.php        # Foo\Bar\Qux\Quux
 *         tests/
 *             BazTest.php         # Foo\Bar\BazTest
 *             Qux/
 *                 QuuxTest.php    # Foo\Bar\Qux\QuuxTest
 *
 * ... add the path to the class files for the \Foo\Bar\ namespace prefix
 * as follows:
 *
 *      <?php
 *      // instantiate the loader
 *      $loader = new \Example\Psr4AutoloaderClass;
 *
 *      // register the autoloader
 *      $loader->register();
 *
 *      // register the base directories for the namespace prefix
 *      $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/src');
 *      $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/tests');
 *
 * The following line would cause the autoloader to attempt to load the
 * \Foo\Bar\Qux\Quux class from /path/to/packages/foo-bar/src/Qux/Quux.php:
 *
 *      <?php
 *      new \Foo\Bar\Qux\Quux;
 *
 * The following line would cause the autoloader to attempt to load the
 * \Foo\Bar\Qux\QuuxTest class from /path/to/packages/foo-bar/tests/Qux/QuuxTest.php:
 *
 *      <?php
 *      new \Foo\Bar\Qux\QuuxTest;
 */
class AutoLoader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @since  1.0.0
     * @access public
     *
     * @param string $prefix         The namespace prefix.
     * @param string $relative_class The relative class name.
     *
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        // Are there any base directories for this namespace prefix?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // Look through base directories for this namespace prefix.
        foreach ($this->prefixes[$prefix] as $base_dir) {
            // Replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php.
            $file = $base_dir
                    . str_replace('\\', '/', $relative_class)
                    . '.php';

            // If the mapped file exists, require it.
            if ($this->requireFile($file)) {
                // Yes, we're done.
                return $file;
            }
        }

        // Never found it.
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @since  1.0.0
     * @access public
     *
     * @param string $file The file to require.
     *
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;

            return true;
        }

        return false;
    }

    /**
     * Register loader with SPL auto-loader stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Add NameSpaces
     *
     * Add multiple namespaces at once
     *
     * @since  1.0.0
     * @access public
     *
     * @param array $array The list of the namespaces to add.
     *
     * @return void
     */
    public function addNamespaces(array $array)
    {
        foreach ($array as $item) {
            $this->addNamespace($item[0], $item[1], ! empty($item[2]) ?: false);
        }
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @since  1.0.0
     * @access public
     *
     * @param string $prefix   The namespace prefix.
     * @param string $base_dir A base directory for class files in the
     *                         namespace.
     * @param bool   $prepend  If true, prepend the base directory to the stack
     *                         instead of appending it; this causes it to be searched first rather
     *                         than last.
     *
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // Normalize namespace prefix.
        $prefix = trim($prefix, '\\') . '\\';

        // Normalize the base directory with a trailing separator.
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // Initialize the namespace prefix array.
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // Retain the base directory for the namespace prefix.
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    /**
     * Loads the class file for a given class name.
     *
     * @since  1.0.0
     * @access public
     *
     * @param string $class The fully-qualified class name.
     *
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        // The current namespace prefix.
        $prefix = $class;

        // Work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name.
        while (false !== $pos = strpos($prefix, '\\')) {
            // Retain the trailing namespace separator in the prefix.
            $prefix = substr($class, 0, $pos + 1);

            // The rest is the relative class name.
            $relative_class = substr($class, $pos + 1);

            // Try to load a mapped file for the prefix and relative class.
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // Remove the trailing namespace separator for the next iteration
            // of strrpos().
            $prefix = rtrim($prefix, '\\');
        }

        // Never found a mapped file.
        return false;
    }
}
