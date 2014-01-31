<?php

/**
 * Add online boolean column
 *
 * @author Studio Echo / Lionel Bouzonville
 */
class OnlineBehavior extends Behavior {

  // default parameters value
  protected $parameters = array(
      'online_column' => 'online'
  );

  /**
   * Add the online_column to the current table
   */
  public function modifyTable() {
    if (!$this->getTable()->containsColumn($this->getParameter('online_column'))) {
      $this->getTable()->addColumn(array(
          'name' => $this->getParameter('online_column'),
          'type' => 'BOOLEAN'
      ));
    }
  }

  /**
   * Get the setter of one of the columns of the behavior
   * 
   * @param     string $column 'online_column'
   * @return    string The related setter & getter
   */
  protected function getColumnSetter($column) {
    return 'set' . $this->getColumnForParameter($column)->getPhpName();
  }

  protected function getColumnConstant($columnName, $builder) {
    return $builder->getColumnConstant($this->getColumnForParameter($columnName));
  }

}

