<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContactosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContactosTable Test Case
 */
class ContactosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ContactosTable
     */
    public $Contactos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Contactos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Contactos') ? [] : ['className' => ContactosTable::class];
        $this->Contactos = TableRegistry::getTableLocator()->get('Contactos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Contactos);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
