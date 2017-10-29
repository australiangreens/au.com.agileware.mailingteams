<?php

use CRM_Team_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Test class for TeamMailing BAO and it's methods.
 * @group headless
 */
class CRM_Team_BAO_TeamMailingTest extends CiviUnitTestCase implements HeadlessInterface {

  public function setUpHeadless() {
    // Comment out following code to setup headless database for tests.
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
   * Test that TeamMailing is not created with empty params.
   */
  public function testCreateWithEmptyParams() {
    $params = array();
    $teamMailing = CRM_Team_BAO_TeamMailing::create($params);
    $this->assertNull($teamMailing);
  }

  /**
   * Test that TeamMailing is not created with only team_id.
   */
  public function testCreateWithOnlyTeam() {
    $team = $this->createTeam();
    $params = array(
      "team_id" => $team->id
    );
    $teamMailing = CRM_Team_BAO_TeamMailing::create($params);
    $this->assertNull($teamMailing);
  }

  /**
   * Test that TeamMailing is not created with only mailing_id.
   */
  public function testCreateWithOnlyMailing() {
    $mailing = $this->createMailing();
    $params = array(
      "mailing_id" => $mailing
    );
    $teamMailing = CRM_Team_BAO_TeamMailing::create($params);
    $this->assertNull($teamMailing);
  }

  /**
   * Test that TeamMailing is created with required parameters.
   */
  public function testCreateWithRequiredParams() {
    $mailing = $this->createMailing();
    $team = $this->createTeam();
    $params = array(
      "mailing_id" => $mailing,
      "team_id"    => $team->id,
    );
    $teamMailing = CRM_Team_BAO_TeamMailing::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailing",$teamMailing,'Check for created object.');
    $this->assertEquals($mailing,$teamMailing->mailing_id,'Check for mailing_id');
    $this->assertEquals($team->id,$teamMailing->team_id,'Check for team_id');
  }

  /**
   * Test that TeamMailings are found by team.
   */
  public function testFindTeamMailingsByTeam() {
    $mailing = $this->createMailing();
    $team = $this->createTeam();
    $params = array(
      "mailing_id" => $mailing,
      "team_id"    => $team->id,
    );
    $teamMailing = CRM_Team_BAO_TeamMailing::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailing",$teamMailing,'Check for created object.');

    $searchParam = array(
      "team_id" => $team->id
    );

    $teamMailing = new CRM_Team_BAO_TeamMailing();
    $teamMailing->copyValues($searchParam);
    $this->assertEquals(1,$teamMailing->find(TRUE),"Check for TeamMailings found.");
  }

  /**
   * Test that single TeamMailings is found by team and mailing.
   */
  public function testFindSingleTeamMailings() {
    $mailing = $this->createMailing();
    $team = $this->createTeam();
    $params = array(
      "mailing_id" => $mailing,
      "team_id"    => $team->id,
    );
    $teamMailing = CRM_Team_BAO_TeamMailing::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailing",$teamMailing,'Check for created object.');

    $teamMailing = new CRM_Team_BAO_TeamMailing();
    $teamMailing->copyValues($params);
    $this->assertEquals(1,$teamMailing->find(TRUE),"Check for TeamMailing found.");
  }

  /**
   * Test that TeamMailings are found and deleted by team.
   */
  public function testDeleteTeamMailingsByTeam() {
    $mailing = $this->createMailing();
    $team = $this->createTeam();
    $params = array(
      "mailing_id" => $mailing,
      "team_id"    => $team->id,
    );
    $teamMailing = CRM_Team_BAO_TeamMailing::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailing",$teamMailing,'Check for created object.');

    $searchParam = array(
      "team_id" => $team->id
    );

    $teamMailing = new CRM_Team_BAO_TeamMailing();
    $teamMailing->copyValues($searchParam);
    $this->assertEquals(1,$teamMailing->delete(),"Check if TeamMailings deleted.");
  }

  /**
   * Test that Single TeamMailing is found and deleted by team and mailing.
   */
  public function testDeleteSingleTeamMailing() {
    $mailing = $this->createMailing();
    $team = $this->createTeam();
    $params = array(
      "mailing_id" => $mailing,
      "team_id"    => $team->id,
    );
    $teamMailing = CRM_Team_BAO_TeamMailing::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailing",$teamMailing,'Check for created object.');

    $teamMailing = new CRM_Team_BAO_TeamMailing();
    $teamMailing->copyValues($params);
    $this->assertEquals(1,$teamMailing->delete(),"Check if TeamMailings deleted.");
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

}
