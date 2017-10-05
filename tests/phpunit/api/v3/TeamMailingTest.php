<?php

use CRM_Team_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Test class for TeamMailing API and it's methods.
 * @group headless
 */
class api_v3_TeamMailingTest extends CiviUnitTestCase implements HeadlessInterface {

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
    $this->callAPIFailure('TeamMailing', 'create', array());
  }

  /**
   * Test that TeamMailing is not created with only team_id.
   */
  public function testCreateWithOnlyTeam() {
    $team = $this->createTeam();
    $params = array(
      "team_id" => $team->id
    );
    $this->callAPIFailure('TeamMailing', 'create', $params);
  }

  /**
   * Test that TeamMailing is not created with only mailing_id.
   */
  public function testCreateWithOnlyMailing() {
    $mailing = $this->createMailing();
    $params = array(
      "mailing_id" => $mailing
    );
    $this->callAPIFailure('TeamMailing', 'create', $params);
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

    $result = $this->callAPISuccess('TeamMailing', 'create', $params);
    $this->assertEquals(1,$result["count"],'Check for created object count.');
    $this->assertEquals($mailing,$result["values"][$result["id"]]["mailing_id"],'Check for mailing_id');
    $this->assertEquals($team->id,$result["values"][$result["id"]]["team_id"],'Check for team_id');
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
    $result = $this->callAPISuccess('TeamMailing', 'create', $params);
    $this->assertEquals(1,$result["count"],'Check for created object count.');

    $searchParam = array(
      "team_id" => $team->id
    );

    $result = $this->callAPISuccess('team', 'get', $searchParam);
    $this->assertEquals(1, $result["count"], 'Check if TeamMailing is found.');
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
    $result = $this->callAPISuccess('TeamMailing', 'create', $params);
    $this->assertEquals(1,$result["count"],'Check for created object count.');

    $result = $this->callAPISuccess('team', 'get', $params);
    $this->assertEquals(1, $result["count"], 'Check if TeamMailing is found.');
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
    $result = $this->callAPISuccess('TeamMailing', 'create', $params);
    $this->assertEquals(1,$result["count"],'Check for created object count.');

    $searchParam = array(
      "team_id" => $team->id
    );

    $result = $this->callAPISuccess('team', 'delete', $searchParam);
    $this->assertEquals(1, $result["count"], 'Check if TeamMailing is deleted.');
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
    $result = $this->callAPISuccess('TeamMailing', 'create', $params);
    $this->assertEquals(1,$result["count"],'Check for created object count.');

    $result = $this->callAPISuccess('team', 'delete', $params);
    $this->assertEquals(1, $result["count"], 'Check if TeamMailing is deleted.');
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
