<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiciosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiciosTable Test Case
 */
class ServiciosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiciosTable
     */
    public $Servicios;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Servicios',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Servicios') ? [] : ['className' => ServiciosTable::class];
        $this->Servicios = TableRegistry::getTableLocator()->get('Servicios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Servicios);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
