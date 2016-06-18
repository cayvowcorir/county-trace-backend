<?php
include (dirname(__FILE__)."/../src/Migrations/Migration.php");

use App\Migration\Migration;

class MyFirstMigration extends Migration
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
    public function change()
    {


      $table = $this->table('block', array('id' => 'blockId'));
        $table->addColumn('name', 'string')
              ->addColumn('level', 'string')
              ->addColumn('imageUrl', 'string', array('null' => true))
               ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->create();

      $table = $this->table('citizen');
        $table->addColumn('citizenName', 'string')
              ->addColumn('citizenEmail', 'string')
              ->addColumn('citizenPassword', 'string')
              ->addColumn('citizenImageUrl', 'string', array('null' => true))
               ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addIndex('citizenEmail', array('unique' => true))
              ->create();

      $table = $this->table('leader');
              ->addColumn('position', 'string')
              ->addColumn('password', 'string')
              ->addColumn('name', 'string')
              ->addColumn('email', 'string')
              ->addColumn('imageUrl', 'string', array('null' => true))
              ->addColumn('blockId', 'integer')
              ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
                ->addForeignKey('blockId', 'block', 'blockId', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->create();

      $table = $this->table('session');
        $table->addColumn('citizenId', 'integer')
              ->addColumn('token', 'string')
              ->addColumn('userAgent', 'string')
              ->addColumn('loginDate', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('active', 'boolean')
               ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->create();

              //Add expiry date on tokens

      $table = $this->table('project', array('id' => 'projectId'));
        $table->addColumn('projectName', 'string')
              ->addColumn('projectDescription', 'string')
              ->addColumn('projectStartDate', 'datetime')
              ->addColumn('projectEndDate', 'datetime', array('null' => true))
              ->addColumn('projectStatus', 'string')
              ->addColumn('blockId', 'integer')
              ->addColumn('citizenId', 'integer')
              ->addColumn('projectBudget', 'string')
               ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addForeignKey('blockId', 'block', 'blockId', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->addForeignKey('citizenId', 'citizen', 'id', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->create();


      $table = $this->table('publicConcern', array('id' => 'publicConcernId'));
        $table->addColumn('publicConcernName', 'string')
              ->addColumn('publicConcernDescription', 'string')
              ->addColumn('publicConcernImageUrl', 'string', array('null' => true))
              ->addColumn('addressee', 'string', array('null' => true))
              ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('citizenId', 'integer')
              ->addColumn('blockId', 'integer')
              ->addForeignKey('blockId', 'block', 'blockId', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->addForeignKey('citizenId', 'citizen', 'id', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->create();


      $table = $this->table('projectRating');
        $table->addColumn('projectId', 'integer')
              ->addColumn('citizenId', 'integer')
               ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addForeignKey('projectId', 'project', 'projectId', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
              ->addForeignKey('citizenId', 'citizen', 'id', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->create();

      $table = $this->table('projectComment', array('id' => 'commentId'));
        $table->addColumn('commentBody', 'string')
              ->addColumn('projectId', 'integer')
              ->addColumn('commentDate', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
               ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addForeignKey('projectId', 'project', 'projectId', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
              ->create();

      $table = $this->table('publicConcernComment', array('id' => 'commentId'));
        $table->addColumn('commentBody', 'string')
              ->addColumn('publicConcernId', 'integer')
              ->addColumn('commentDate', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
               ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('updated_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))

              ->create();



    }
}
