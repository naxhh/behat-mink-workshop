<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

require_once __DIR__.'/../../vendor/phpunit/phpunit/PHPUnit/Autoload.php';
require_once __DIR__.'/../../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

/**
 * Behat context class.
 */
class ApiContext implements SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct($url)
    {
        $this->client = new GuzzleHttp\Client();
        $this->base_url = $url;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE) ([^"]*)"$/
     */
    public function iRequest($raw_method, $resource)
    {
        $method = strtolower($raw_method);

        try {
            $this->resource = $this->client->{$method}(
                $this->base_url . $resource
            );

            $this->scope = $this->result = $this->resource->json();

        } catch (GuzzleHttp\Exception\ClientException $e) {
            $this->error_code = $e->getCode();
            # super cute...
            if ( $e->getCode() != '404' )
            {
                throw $e;
            }
        }
    }

    /**
     * @Then I get a :code response
     */
    public function iGetAResponse($expected_code)
    {
        $code = isset($this->error_code) ? $this->error_code : $this->resource->getStatusCode();

        assertEquals($expected_code, $code);
    }

    /**
     * @Then scope into :cat cat
     */
    public function scopeIntoCat($cat)
    {
        $this->scope = $this->scope[$cat];
    }

    /**
     * @Then the properties exist:
     */
    public function thePropertiesExist(PyStringNode $properties)
    {
        foreach ($properties->getStrings() as $property) {
            $this->thePropertieExist($property);
        }
    }

    /**
    * @Then the property :property exist
    */
    public function thePropertieExist($property)
    {
        list($key, $res) = $this->deconstructProperty($property);

        assertArrayHasKey($key, $res);
    }

    /**
     * @Then :property should be a :type
     * @Then :property should be an :type
     */
    public function propertyShouldBeA($property, $type)
    {
        list($key,$res) = $this->deconstructProperty($property);

        $types_map = array(
            'list' => 'array',
            'object' => 'array',
        );

        $type = array_key_exists($type, $types_map) ? $types_map[$type] : $type;

        assertInternalType($type, $res[$key]);
    }

    private function deconstructProperty($property)
    {
        $levels = explode('.', $property);
        $key = array_pop( $levels );

        $res = $this->scope;
        foreach ($levels as $level) {
            $res = $res[$level];
        }

        return array($key, $res);
    }
}
