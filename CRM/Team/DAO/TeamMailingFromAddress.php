<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 4.7                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2017                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
*/
/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2017
 *
 * Generated from xml/schema/CRM/Team/TeamMailingFromAddress.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:ddaf11c752fef0e826b6c08c204c55ba)
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
/**
 * CRM_Team_DAO_TeamMailingFromAddress constructor.
 */
class CRM_Team_DAO_TeamMailingFromAddress extends CRM_Core_DAO {
  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_team_mailing_email';
  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var boolean
   */
  static $_log = true;
  /**
   * Unique TeamMailingFromAddress ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * FK to civicrm_team
   *
   * @var int unsigned
   */
  public $team_id;
  /**
   * From Email Address option value
   *
   * @var int unsigned
   */
  public $from_email_address_id;
  /**
   * Class constructor.
   */
  function __construct() {
    $this->__table = 'civicrm_team_mailing_email';
    parent::__construct();
  }
  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static ::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'team_id', 'civicrm_team', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }
  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'Unique TeamMailingFromAddress ID',
          'required' => true,
          'table_name' => 'civicrm_team_mailing_email',
          'entity' => 'TeamMailingFromAddress',
          'bao' => 'CRM_Team_DAO_TeamMailingFromAddress',
          'localizable' => 0,
        ) ,
        'team_id' => array(
          'name' => 'team_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'FK to civicrm_team',
          'required' => true,
          'table_name' => 'civicrm_team_mailing_email',
          'entity' => 'TeamMailingFromAddress',
          'bao' => 'CRM_Team_DAO_TeamMailingFromAddress',
          'localizable' => 0,
          'FKClassName' => 'CRM_Team_DAO_Team',
        ) ,
        'from_email_address_id' => array(
          'name' => 'from_email_address_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'From Email Address option value',
          'required' => true,
          'table_name' => 'civicrm_team_mailing_email',
          'entity' => 'TeamMailingFromAddress',
          'bao' => 'CRM_Team_DAO_TeamMailingFromAddress',
          'localizable' => 0,
          'pseudoconstant' => array(
            'optionGroupName' => 'from_email_address',
            'optionEditPath' => 'civicrm/admin/options/from_email_address',
          )
        ) ,
      );
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }
  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }
  /**
   * Returns the names of this table
   *
   * @return string
   */
  static function getTableName() {
    return self::$_tableName;
  }
  /**
   * Returns if this table needs to be logged
   *
   * @return boolean
   */
  function getLog() {
    return self::$_log;
  }
  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &import($prefix = false) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'team_mailing_email', $prefix, array());
    return $r;
  }
  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &export($prefix = false) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'team_mailing_email', $prefix, array());
    return $r;
  }
  /**
   * Returns the list of indices
   */
  public static function indices($localize = TRUE) {
    $indices = array(
      'UI_team_mailing_from_address_option_id' => array(
        'name' => 'UI_team_mailing_from_address_option_id',
        'field' => array(
          0 => 'team_id',
          1 => 'from_email_address_id',
        ) ,
        'localizable' => false,
        'unique' => true,
        'sig' => 'civicrm_team_mailing_email::1::team_id::from_email_address_id',
      ) ,
    );
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }
}
