<?php

use Phinx\Migration\AbstractMigration;

class UserAddPasswordHashAlgo extends AbstractMigration
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
        $table = $this->table('users');
        if (!$table->hasColumn('password_hash_algo') && !$table->hasColumn('new_password')) {
            $table->addColumn('password_hash_algo', 'string', ['limit' =>  50, 'default' => 'md5'])
                ->addColumn('new_password', 'string', ['limit' => 255])
                ->save();
        }

        $users = $this->fetchAll('SELECT * FROM users');

        foreach ($users as $user) {
            $new_password = password_hash($user['password'], PASSWORD_DEFAULT);
            $this->execute('
                UPDATE users SET
                new_password=\'' . $new_password . '\'
                WHERE id=\'' . $user['id'] . '\'
            ');
        }
    }

    public function down()
    {
        $table = $this->table('users');
        $table
            ->removeColumn('password_hash_algo')
            ->removeColumn('new_password')
            ->save();
    }
}
