<?php

class CRM_Team_BAO_TeamMailing extends CRM_Team_DAO_TeamMailing {

  /**
   * Create a new TeamMailing based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Team_DAO_TeamMailing|NULL
   *
   */
  public static function create($params) {

    if(!isset($params["team_id"]) || empty($params["team_id"]) || !isset($params["mailing_id"]) || empty($params["mailing_id"])) {
      return NULL;
    }

    $className = 'CRM_Team_DAO_TeamMailing';
    $entityName = 'TeamMailing';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }
}
