<?php
// tests for our mini-app

/**
 * A class to mimic the session var saving
 */
class FakeSessionCallback extends \Symfony\Component\HttpFoundation\Session\Session
{   
    protected $bag = array();

    public function get($name, $default = NULL)
    {
        return isset($this->bag[$name]) ? $this->bag[$name] : $default;
    }

    public function set($name, $value)
    {
        $this->bag[$name] = $value;
    }
}

/**
* Test Cases for bootstrap_flash function
*/
class BootstrapFlashCase extends \PHPUnit_Framework_TestCase
{   
    /**
     * Silex\Application instance
     */
    protected $app;

    /**
     * the flash content 
     **/
    protected $flash_msg = array('type' => 'notice', 'message' => 'Hey there.');

    public function setUp()
    {
        $app = new Silex\Application();

        //actualy use twig
        $app->register(new Silex\Provider\TwigServiceProvider(), array(
            'twig.path' => __DIR__.'/views',
            'twig.options' => array('cache' => __DIR__.'/../cache'),
            ));

        //add the fake session
        $app['session'] = new FakeSessionCallback();

        //add the flash message
        $app['session']->set('flash', $this->flash_msg);

        $this->app = $app;
    }

    /**
     * Test the flash message bootstrapping for twig templates
     */
    public function testBootstraping()
    {
        $has_flash = function($twig) {
            return isset($twig->getGlobals()['flash']);
        };

        // empty flash message?? ok, ok...
        $this->assertFalse($has_flash($this->app['twig']));

        // bootstrap the message
        bootstrap_flash($this->app['session'], $this->app['twig']);

        // And do we have a winner?
        $this->assertEquals($this->flash_msg, 
            $this->app['twig']->getGlobals()['flash'], 
            "Can't retrieve the flash message from twig.");
    }

    /**
     * test the session flash var refresh
     */
    public function testFlashRefresh()
    {
        // ok, the session var "flash is here"
        $this->assertEquals($this->flash_msg,
            $this->app['session']->get('flash'));

        // bootstrap flash messages
        bootstrap_flash($this->app['session'], $this->app['twig']);

        // and it should not be here anymore
        $this->assertNull($this->app['session']->get('flash'),
            'The session "flash" could not be refreshed');
    }
}