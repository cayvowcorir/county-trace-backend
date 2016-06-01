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
      $table = $this->table('leader', array('id' => false, 'primary_key' => 'leaderId'));
        $table->addColumn('leaderId', 'integer')
              ->addColumn('leaderName', 'string')
              ->addColumn('leaderImageUrl', 'string')
              ->addColumn('leaderElectedFrom', 'datetime')
              ->addColumn('leaderElectedTo', 'datetime')
              ->create();

      $table = $this->table('block', array('id' => false, 'primary_key' => 'blockId'));
        $table->addColumn('blockId', 'integer')
              ->addColumn('name', 'string')
              ->addColumn('level', 'string')
              ->addColumn('imageUrl', 'datetime')
              ->addColumn('leaderElectedTo', 'datetime')
              ->create();

      $table = $this->table('project', array('id' => false, 'primary_key' => 'projectId'));
        $table->addColumn('projectId', 'integer')
              ->addColumn('projectName', 'string')
              ->addColumn('projectDescription', 'string')
              ->addColumn('projectStartDate', 'datetime')
              ->addColumn('projectEndDate', 'datetime')
              ->addColumn('projectStatus', 'string')
              ->addColumn('projectBudget', 'string')
              ->addColumn('projectRating', 'integer')
              ->create();

      $table = $this->table('comment', array('id' => false, 'primary_key' => 'commentId'));
        $table->addColumn('commentId', 'integer')
              ->addColumn('commentBody', 'string')
              ->addColumn('commentDate', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->create();

      $table = $this->table('citizen', array('id' => false, 'primary_key' => 'citizenId'));
        $table->addColumn('citizenId', 'integer')
              ->addColumn('citizenName', 'string')
              ->addColumn('citizenEmail', 'string')
              ->addColumn('citizenImageUrl', 'string')
              ->create();

      $table = $this->table('publicConcern', array('id' => false, 'primary_key' => 'publicConcernId'));
        $table->addColumn('publicConcernId', 'integer')
              ->addColumn('publicConcernName', 'string')
              ->addColumn('publicConcernDescription', 'string')
              ->addColumn('publicConcernImageUrl', 'string')
              ->addColumn('publicConcernDate', 'datetime')
              ->create();
    }
}
