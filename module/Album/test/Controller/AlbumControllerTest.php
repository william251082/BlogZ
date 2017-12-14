<?php
namespace AlbumTest\Controller;

use Album\Model\AlbumTable;
use Zend\ServiceManager\ServiceManager;
use Album\Controller\AlbumController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    protected $albumTable;

    protected function configureServiceManager(ServiceManager $services)
{
    $services->setAllowOverride(true);

    $services->setService('config', $this->updateConfig($services->get('config')));
    $services->setService(AlbumTable::class, $this->mockAlbumTable()->reveal());

    $services->setAllowOverride(false);
}

	protected function updateConfig($config)
{
    $config['db'] = [];
    return $config;
}

	protected function mockAlbumTable()
{
    $this->albumTable = $this->prophesize(AlbumTable::class);
    return $this->albumTable;
}

    public function setUp()
{
    // The module configuration should still be applicable for tests.
    // You can override configuration here with test case specific values,
    // such as sample view templates, path stacks, module_listener_options,
    // etc.
    $configOverrides = [];

    $this->setApplicationConfig(ArrayUtils::merge(
        include __DIR__ . '/../../../../config/application.config.php',
        $configOverrides
    ));

    parent::setUp();

    $this->configureServiceManager($this->getApplicationServiceLocator());
}
    public function testIndexActionCanBeAccessed()
{
    $this->albumTable->fetchAll()->willReturn([]);

    $this->dispatch('/album');
    $this->assertResponseStatusCode(200);
    $this->assertModuleName('Album');
    $this->assertControllerName(AlbumController::class);
    $this->assertControllerClass('AlbumController');
    $this->assertMatchedRouteName('album');
}
}