<?php

use CRM_Team_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Test class for TeamMailingGroup API & It's methods.
 * @group headless
 */
class api_v3_TeamMailingGroupTest extends CiviUnitTestCase implements HeadlessInterface {

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
   * Test that TeamMailingGroup is not created with empty params.
   */
  public function testCreateWithEmptyParams() {
    $this->callAPIFailure('TeamMailingGroup', 'create', array());
  }

  /**
   * Test that TeamMailingGroup is not created with only team.
   */
  public function testCreateWithOnlyTeam() {
    $team = $this->createTeam();
    $params = array(
      "team_id" => $team->id
    );
    $this->callAPIFailure('TeamMailingGroup', 'create', $params);
  }

  /**
   * Test that TeamMailingGroup is not created with only group.
   */
  public function testCreateWithOnlyGroup() {
    $groupid = $this->groupCreate();
    $params = array(
      "group_id" => $groupid
    );
    $this->callAPIFailure('TeamMailingGroup', 'create', $params);
  }

  /**
   * Test that TeamMailingGroup is created with required parameters.
   */
  public function testCreateWithRequiredParams() {
    $groupid = $this->groupCreate(array(
      "name"  => "Agileware Group 1",
      "title" => "Agileware Group 1 created",
      "description" => "Agileware Group 1 created",
    ));
    $team = $this->createTeam();
    $params = array(
      "group_id" => $groupid,
      "team_id"  => $team->id
    );
    $result = $this->callAPISuccess('TeamMailingGroup','create',$params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");

    $this->assertEquals($groupid,$result["values"][$result["id"]]["group_id"],"Check for created group_id");
    $this->assertEquals($team->id,$result["values"][$result["id"]]["team_id"],"Check for created team_id");
  }

  /**
   * Test that TeamMailingGroup is created with all parameters.
   */
  public function testCreateWithAllParams() {
    $groupid = $this->groupCreate(array(
      "name"  => "Agileware Group 2",
      "title" => "Agileware Group 2 created",
      "description" => "Agileware Group 2 created",
    ));
    $team = $this->createTeam();
    $role = "draft";
    $params = array(
      "group_id" => $groupid,
      "team_id"  => $team->id,
      "role"     => $role
    );
    $result = $this->callAPISuccess('TeamMailingGroup','create',$params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");

    $this->assertEquals($groupid,$result["values"][$result["id"]]["group_id"],"Check for created group_id");
    $this->assertEquals($team->id,$result["values"][$result["id"]]["team_id"],"Check for created team_id");
    $this->assertEquals($role,$result["values"][$result["id"]]["role"],"Check for created role.");
  }

  /**
   * Test that TeamMailingGroup is found by team.
   */
  public function testFindTeamMailingGroupByTeam() {
    $groupid = $this->groupCreate(array(
      "name"  => "Agileware Group 3",
      "title" => "Agileware Group 3 created",
      "description" => "Agileware Group 3 created",
    ));
    $team = $this->createTeam();
    $role = "draft";
    $params = array(
      "group_id" => $groupid,
      "team_id"  => $team->id,
      "role"     => $role
    );
    $result = $this->callAPISuccess('TeamMailingGroup','create',$params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");

    $searchParams = array(
      "team_id" => $team->id
    );
    $result = $this->callAPISuccess('TeamMailingGroup','get',$searchParams);
    $this->assertEquals(1,$result["count"],"Check if TeamMailingGroup is found.");
  }

  /**
   * Test that single TeamMailingGroup is found by team and group id.
   */
  public function testFindSingleTeamMailingGroup() {
    $groupid = $this->groupCreate(array(
      "name"  => "Agileware Group 4",
      "title" => "Agileware Group 4 created",
      "description" => "Agileware Group 4 created",
    ));
    $team = $this->createTeam();
    $role = "draft";
    $params = array(
      "group_id" => $groupid,
      "team_id"  => $team->id,
      "role"     => $role
    );
    $result = $this->callAPISuccess('TeamMailingGroup','create',$params);
    $this->assertEquals(1,$result["count"],"Check for created object count.");

    $searchParams = array(
      "team_id" => $team->id,
      "group_id" => $groupid,
    );
    $result = $this->callAPISuccess('TeamMailingGroup','get',$searchParams);
    $this->assertEquals(1,$result["count"],"Check if TeamMailingGroup is found.");
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
