<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BannersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BannersTable Test Case
 */
class BannersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BannersTable
     */
    public $Banners;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Banners',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Banners') ? [] : ['className' => BannersTable::class];
        $this->Banners = TableRegistry::getTableLocator()->get('Banners', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Banners);

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
