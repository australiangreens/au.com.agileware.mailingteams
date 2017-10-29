<?php

class CRM_Team_BAO_TeamMailingFromAddress extends CRM_Team_DAO_TeamMailingFromAddress {

  /**
   * Create a new TeamMailingFromAddress based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Team_DAO_TeamMailingFromAddress|NULL
   *
   */
  public static function create($params) {

    if(!isset($params["team_id"]) || empty($params["team_id"]) || !isset($params["from_email_address_id"]) || empty($params["from_email_address_id"])) {
      return NULL;
    }

    $className = 'CRM_Team_DAO_TeamMailingFromAddress';
    $entityName = 'TeamMailingFromAddress';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  public function addSelectWhereClause() {
    $clauses = parent::addSelectWhereClause();
    $contact_id = CRM_Core_Session::getLoggedInContactID();
    $clauses['team_id'][] = 'IN (SELECT team_id FROM civicrm_team_contact WHERE contact_id = ' . $contact_id . ')';

    return $clauses;
  }
}
