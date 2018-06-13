<?php namespace QL\DocMarkdown;
/**
 * @copyright ©2016 Quicken Loans Inc. All rights reserved. Trade
 * Secret, Confidential and Proprietary. Any dissemination outside
 * of Quicken Loans is strictly prohibited.
 */

/**
 * Class Config
 *
 * NOTE this class only handles configuration settings as strings and no other
 * types.
 *
 * @package \QL\DocMarkdown
 */
class Config
{
    /** @var array */
    private $settings;

    /**
     * Config constructor.
     *
     * When no file or an file with no values is passed in, this configuration
     * will be considered empty, and return NULL for any value retrieved, as
     * opposed to throwing an error.
     *
     * @param string $configFilename A file to load values from.
     */
    public function __construct($configFilename = NULL)
    {
        $this->settings = [];

        if ($configFilename !== NULL && file_exists($configFilename)) {
            $this->loadFromFile($configFilename);
        }
    }

    /**
     * Get a configuration value.
     *
     * @param string $key
     * @return string|NULL Will return the string valued stored or null when it does not exist.
     */
    public function get($key)
    {
        $value = NULL;

        if (array_key_exists($key, $this->settings)) {
            $value = $this->settings[$key];
        }

        return $value;
    }

    /**
     * Store a string value in the configuration.
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function with($key, $value)
    {
        $this->settings[$key] = (string) $value;

        return $this;
    }

    /**
     * Config constructor.
     *
     * When no file or an file with no values is passed in, this configuration
     * will be considered empty, and return NULL for any value retrieved, as
     * opposed to throwing an error.
     *
     * @param string $configFilename A file to load values from.
     * @return $this
     */
    public function loadFromFile($configFilename = NULL)
    {
        if ($configFilename !== NULL && file_exists($configFilename)) {
            $jsonStr = file_get_contents($configFilename);
            $newSettings = json_decode($jsonStr, TRUE);
            $this->settings = array_replace_recursive(
                $this->settings,
                $newSettings
            );
        }

        return $this;
    }
}