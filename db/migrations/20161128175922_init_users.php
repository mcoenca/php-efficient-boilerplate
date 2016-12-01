<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class InitUsers extends AbstractMigration
{
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
	 *
	 * The following commands can be used in this method and Phinx will
	 * automatically reverse them when rolling back:
	 *
	 *    createTable
	 *    renameTable
	 *    addColumn
	 *    renameColumn
	 *    addIndex
	 *    addForeignKey
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function up()
	{
		// // create the table
    $exists = $this->hasTable('users');
    if (!$exists) {
		  $table = $this->table('users');
  		$table
  			->addColumn('mail', 'string', ['limit' => 100])
  			->addColumn('password', 'string', ['limit' => 80])
  			->addColumn('date_create', 'datetime')
  			->addColumn('date_cancel', 'datetime')
  			->addColumn('last_login', 'datetime')
  			->addColumn('civilite', 'string', ['limit' => 20])
  			->addColumn('prenom', 'string', ['limit' => 50])
  			->addColumn('nom', 'string', ['limit' => 50])
  			->addColumn('adress1', 'string', ['limit' => 100])
  			->addColumn('adress2', 'string', ['limit' => 100])
  			->addColumn('city', 'string', ['limit' => 70])
  			->addColumn('zipcode', 'string', ['limit' => 10])
  			->addColumn('country', 'string', ['limit' => 50])
  			->addColumn('telephone', 'string', ['limit' => 20])
  			->addColumn('job', 'string', ['limit' => 50])
  			->create();
    }    
	}
  public function down() {
    //not doing anything actually
  }
}
