<?php

use Mindy\Query\Schema;

/**
 * @group db
 * @group mysql
 */
class SchemaTest extends DatabaseTestCase
{
    public function testGetTableNames()
    {
        /** @var Schema $schema */
        $schema = $this->getConnection()->schema;

        $tables = $schema->getTableNames();
        $this->assertTrue(in_array('tbl_customer', $tables));
        $this->assertTrue(in_array('tbl_category', $tables));
        $this->assertTrue(in_array('tbl_item', $tables));
        $this->assertTrue(in_array('tbl_order', $tables));
        $this->assertTrue(in_array('tbl_order_item', $tables));
        $this->assertTrue(in_array('tbl_type', $tables));
    }

    public function testGetTableSchemas()
    {
        /** @var Schema $schema */
        $schema = $this->getConnection()->schema;

        $tables = $schema->getTableSchemas();
        $this->assertEquals(count($schema->getTableNames()), count($tables));
        foreach ($tables as $table) {
            $this->assertInstanceOf('Mindy\Query\TableSchema', $table);
        }
    }

    public function testGetNonExistingTableSchema()
    {
        $this->assertNull($this->getConnection()->schema->getTableSchema('nonexisting_table'));
    }

    public function testSchemaCache()
    {
        if(!class_exists('\Mindy\Cache\FileCache')) {
            $this->markTestSkipped('Class Mindy\Cache\FileCache not found. Please install mindy\cache');
        }
        /** @var Schema $schema */
        $schema = $this->getConnection()->schema;

        $schema->db->enableSchemaCache = true;
        $schema->db->schemaCache = new Mindy\Cache\FileCache([
            'cachePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'cache'
        ]);
        $noCacheTable = $schema->getTableSchema('tbl_type', true);
        $cachedTable = $schema->getTableSchema('tbl_type', true);
        $this->assertEquals($noCacheTable, $cachedTable);
    }

    public function testCompositeFk()
    {
        /** @var Schema $schema */
        $schema = $this->getConnection()->schema;

        $table = $schema->getTableSchema('tbl_composite_fk');

        $this->assertCount(1, $table->foreignKeys);
        $this->assertTrue(isset($table->foreignKeys[0]));
        $this->assertEquals('tbl_order_item', $table->foreignKeys[0][0]);
        $this->assertEquals('order_id', $table->foreignKeys[0]['order_id']);
        $this->assertEquals('item_id', $table->foreignKeys[0]['item_id']);
    }

    public function testGetPDOType()
    {
        $values = [
            [null, \PDO::PARAM_NULL],
            ['', \PDO::PARAM_STR],
            ['hello', \PDO::PARAM_STR],
            [0, \PDO::PARAM_INT],
            [1, \PDO::PARAM_INT],
            [1337, \PDO::PARAM_INT],
            [true, \PDO::PARAM_BOOL],
            [false, \PDO::PARAM_BOOL],
            [$fp = fopen(__FILE__, 'rb'), \PDO::PARAM_LOB],
        ];

        /** @var Schema $schema */
        $schema = $this->getConnection()->schema;

        foreach ($values as $value) {
            $this->assertEquals($value[1], $schema->getPdoType($value[0]));
        }
        fclose($fp);
    }
}
