<?php

use CRM_Team_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Test class for TeamMailingFromAddress API & It's methods.
 * @group headless
 */
class api_v3_TeamMailingFromAddressTest extends CiviUnitTestCase implements HeadlessInterface {

  public function setUpHeadless() {
    // Comment out following block to setup headless database.
    /*return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();*/
  }

  public function setUp() {
    parent::setUp();
  }

  public function tearDown() {
    parent::tearDown();
  }

  /**
   * Test that TeamMailingFromAddress is not created with empty params.
   */
  public function testCreateWithEmptyParams() {
    $this->callAPIFailure('TeamMailingFromAddress', 'create', array());
  }

  /**
   * Test that TeamMailingFromAddress is not created with only team.
   */
  public function testCreateWithOnlyTeam() {
    $team = $this->createTeam();
    $params = array(
      "team_id" => $team->id
    );
    $this->callAPIFailure('TeamMailingFromAddress', 'create', $params);
  }

  /**
   * Test that TeamMailingFromAddress is not created with only from emaiil.
   */
  public function testCreateWithOnlyFromEmail() {
    $fromemail = $this->createFromEmail(2);
    $params = array(
      "from_email_address_id" => $fromemail->value
    );
    $this->callAPIFailure('TeamMailingFromAddress', 'create', $params);
  }

  /**
   * Test that TeamMailingFromAddress is created with required params.
   */
  public function testCreateWithRequiredParams() {
    $fromemail = $this->createFromEmail(2);
    $team = $this->createTeam();
    $params = array(
      "from_email_address_id" => $fromemail->value,
      "team_id"               => $team->id
    );
    $result = $this->callAPISuccess('TeamMailingFromAddress', 'create', $params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");
    $this->assertEquals($fromemail->value,$result["values"][$result["id"]]["from_email_address_id"],"Check for from email address id");
    $this->assertEquals($team->id,$result["values"][$result["id"]]["team_id"],"Check for team id");
  }

  /**
   * Test that TeamMailingFromAddress is found by team.
   */
  public function testFindTeamMailingEmailByTeam() {
    $fromemail = $this->createFromEmail(2);
    $team = $this->createTeam();
    $params = array(
      "from_email_address_id" => $fromemail->value,
      "team_id"               => $team->id
    );
    $result = $this->callAPISuccess('TeamMailingFromAddress', 'create', $params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");

    $searchParams = array(
      "team_id" => $team->id
    );

    $result = $this->callAPISuccess('TeamMailingFromAddress', 'get', $searchParams);
    $this->assertEquals(1,$result["count"],"Check TeamMailingFromAddress found.");
  }

  /**
   * Test that single TeamMailingFromAddress is found by team and from_email_address_id.
   */
  public function testFindSingleTeamMailingEmail() {
    $fromemail = $this->createFromEmail(2);
    $team = $this->createTeam();
    $params = array(
      "from_email_address_id" => $fromemail->value,
      "team_id"               => $team->id
    );
    $result = $this->callAPISuccess('TeamMailingFromAddress', 'create', $params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");

    $result = $this->callAPISuccess('TeamMailingFromAddress', 'get', $params);
    $this->assertEquals(1,$result["count"],"Check TeamMailingFromAddress found.");
  }

  /**
   * Test that TeamMailingFromAddress is found & deleted by team.
   */
  public function testDeleteTeamMailingEmailByTeam() {
    $fromemail = $this->createFromEmail(2);
    $team = $this->createTeam();
    $params = array(
      "from_email_address_id" => $fromemail->value,
      "team_id"               => $team->id
    );
    $result = $this->callAPISuccess('TeamMailingFromAddress', 'create', $params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");

    $searchParams = array(
      "team_id" => $team->id
    );

    $result = $this->callAPISuccess('TeamMailingFromAddress', 'get', $searchParams);
    $this->assertEquals(1,$result["count"],"Check TeamMailingFromAddress deleted.");
  }

  /**
   * Test that single TeamMailingFromAddress is found & deleted by team and from_email_address_id.
   */
  public function testDeleteSingleTeamMailingEmail() {
    $fromemail = $this->createFromEmail(2);
    $team = $this->createTeam();
    $params = array(
      "from_email_address_id" => $fromemail->value,
      "team_id"               => $team->id
    );
    $result = $this->callAPISuccess('TeamMailingFromAddress', 'create', $params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");

    $result = $this->callAPISuccess('TeamMailingFromAddress', 'get', $params);
    $this->assertEquals(1,$result["count"],"Check TeamMailingFromAddress deleted.");
  }

  /*
   * Creating a team for test
   */
  private function createTeam() {
    $teamName = "Agileware Team";
    $params = array(
      "team_name" => $teamName
    );
    $team = CRM_Team_BAO_Team::create($params);
    return $team;
  }

  /*
   * Creating a from email for test
   */
  private function createFromEmail($value) {
    $groupName = "from_email_address";
    $params = array(
      "name" => $groupName
    );
    $optionGroup = new CRM_Core_BAO_OptionGroup();
    $optionGroup->copyValues($params);
    $optionGroup->find(TRUE);
    $optionGroupId = $optionGroup->id;

    $fromEmail = '"Agileware Team" <testemaiil@domain.com>';

    $optionValueParams = array(
      "option_group_id" => $optionGroupId,
      "label"           => $fromEmail,
      "name"            => $fromEmail,
      "value"           => $value,
      "weight"          => 2
    );

    $optionValue = CRM_Core_BAO_OptionValue::create($optionValueParams);
    return $optionValue;
  }

}
