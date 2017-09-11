<?php

class CRM_MailingTeam_APIWrapper implements API_Wrapper {
  public function fromAPIInput($apiRequest) {
    CRM_Team_BAO_TeamMailingGroup::$doAclCheck = TRUE;
    unset($apiRequest['params']['params']['forMailing']);
    return $apiRequest;
  }

  public function toApiOutput($apiRequest, $result){
    CRM_Team_BAO_TeamMailingGroup::$doAclCheck = FALSE;
    return $result;
  }
}