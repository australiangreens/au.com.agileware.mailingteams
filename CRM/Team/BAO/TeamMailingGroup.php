<?php

class CRM_Team_BAO_TeamMailingGroup extends CRM_Team_DAO_TeamMailingGroup {
  public static $doAclCheck = FALSE;

  /**
   * Create a new TeamMailingGroup based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Team_DAO_TeamMailingGroup|NULL
   *
  public static function create($params) {
    $className = 'CRM_Team_DAO_TeamMailingGroup';
    $entityName = 'TeamMailingGroup';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

  public function addSelectWhereClause() {
    $clauses = parent::addSelectWhereClause();
    $contact_id = CRM_Core_Session::getLoggedInContactID();
    $clauses['team_id'][] = 'IN (SELECT team_id FROM civicrm_team_contact WHERE contact_id = ' . $contact_id . ')';

    return $clauses;
  }
}
