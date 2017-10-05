<?php

use CRM_Team_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Test class for TeamMailingFromAddress BAO & It's methods.
 * @group headless
 */
class CRM_Team_BAO_TeamMailingFromAddressTest extends CiviUnitTestCase implements HeadlessInterface {

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
    $params = array();
    $teammailingfromaddress = CRM_Team_BAO_TeamMailingFromAddress::create($params);
    $this->assertNull($teammailingfromaddress);
  }

  /**
   * Test that TeamMailingFromAddress is not created with only team.
   */
  public function testCreateWithOnlyTeam() {
    $team = $this->createTeam();
    $params = array(
      "team_id" => $team->id
    );
    $teammailingfromaddress = CRM_Team_BAO_TeamMailingFromAddress::create($params);
    $this->assertNull($teammailingfromaddress);
  }

  /**
   * Test that TeamMailingFromAddress is not created with only from emaiil.
   */
  public function testCreateWithOnlyFromEmail() {
    $fromemail = $this->createFromEmail(2);
    $params = array(
      "from_email_address_id" => $fromemail->value
    );
    $teammailingfromaddress = CRM_Team_BAO_TeamMailingFromAddress::create($params);
    $this->assertNull($teammailingfromaddress);
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
    $teammailingfromaddress = CRM_Team_BAO_TeamMailingFromAddress::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingFromAddress",$teammailingfromaddress,"Check for created object.");
    $this->assertEquals($fromemail->value,$teammailingfromaddress->from_email_address_id,"Check for from email address id");
    $this->assertEquals($team->id,$teammailingfromaddress->team_id,"Check for team id");
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
    $teammailingfromaddress = CRM_Team_BAO_TeamMailingFromAddress::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingFromAddress",$teammailingfromaddress,"Check for created object.");

    $searchParams = array(
      "team_id" => $team->id
    );

    $teammailingfromaddress = new CRM_Team_BAO_TeamMailingFromAddress();
    $teammailingfromaddress->copyValues($searchParams);
    $this->assertEquals(1,$teammailingfromaddress->find(TRUE),"Check TeamMailingFromAddress count.");
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
    $teammailingfromaddress = CRM_Team_BAO_TeamMailingFromAddress::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingFromAddress",$teammailingfromaddress,"Check for created object.");

    $teammailingfromaddress = new CRM_Team_BAO_TeamMailingFromAddress();
    $teammailingfromaddress->copyValues($params);
    $this->assertEquals(1,$teammailingfromaddress->find(TRUE),"Check TeamMailingFromAddress count.");
  }

  /**
   * Test that TeamMailingFromAddress is deleted by team.
   */
  public function testDeleteTeamMailingEmailByTeam() {
    $fromemail = $this->createFromEmail(2);
    $team = $this->createTeam();
    $params = array(
      "from_email_address_id" => $fromemail->value,
      "team_id"               => $team->id
    );
    $teammailingfromaddress = CRM_Team_BAO_TeamMailingFromAddress::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingFromAddress",$teammailingfromaddress,"Check for created object.");

    $searchParams = array(
      "team_id" => $team->id
    );

    $teammailingfromaddress = new CRM_Team_BAO_TeamMailingFromAddress();
    $teammailingfromaddress->copyValues($searchParams);
    $this->assertEquals(1,$teammailingfromaddress->delete(),"Check TeamMailingFromAddress delete count.");
  }

  /**
   * Test that single TeamMailingFromAddress is deleted by team and from_email_address_id.
   */
  public function testDeleteSingleTeamMailingEmail() {
    $fromemail = $this->createFromEmail(2);
    $team = $this->createTeam();
    $params = array(
      "from_email_address_id" => $fromemail->value,
      "team_id"               => $team->id
    );
    $teammailingfromaddress = CRM_Team_BAO_TeamMailingFromAddress::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingFromAddress",$teammailingfromaddress,"Check for created object.");

    $teammailingfromaddress = new CRM_Team_BAO_TeamMailingFromAddress();
    $teammailingfromaddress->copyValues($params);
    $this->assertEquals(1,$teammailingfromaddress->delete(),"Check TeamMailingFromAddress delete count.");
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
