<?php

use CRM_Team_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Test class for TeamMailingGroup BAO and its methods.
 * @group headless
 */
class CRM_Team_BAO_TeamMailingGroupTest extends CiviUnitTestCase implements HeadlessInterface {

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
    $params = array();
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertNull($teamMailingGroup);
  }

  /**
   * Test that TeamMailingGroup is not created with only team.
   */
  public function testCreateWithOnlyTeam() {
    $team = $this->createTeam();
    $params = array(
      "team_id" => $team->id
    );
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertNull($teamMailingGroup);
  }

  /**
   * Test that TeamMailingGroup is not created with only group.
   */
  public function testCreateWithOnlyGroup() {
    $groupid = $this->groupCreate();
    $params = array(
      "group_id" => $groupid
    );
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertNull($teamMailingGroup);
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
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingGroup",$teamMailingGroup,"Check for created object.");
    $this->assertEquals($groupid,$teamMailingGroup->group_id,"Check for created group_id");
    $this->assertEquals($team->id,$teamMailingGroup->team_id,"Check for created team_id");
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
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingGroup",$teamMailingGroup,"Check for created object.");
    $this->assertEquals($groupid,$teamMailingGroup->group_id,"Check for created group_id.");
    $this->assertEquals($team->id,$teamMailingGroup->team_id,"Check for created team_id.");
    $this->assertEquals($role,$teamMailingGroup->role,"Check for created role.");
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
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingGroup",$teamMailingGroup,"Check for created object.");

    $searchParams = array(
      "team_id" => $team->id
    );
    $teamMailingGroup = new CRM_Team_BAO_TeamMailingGroup();
    $teamMailingGroup->copyValues($searchParams);
    $this->assertEquals(1,$teamMailingGroup->find(TRUE),"Check for TeamMailingGroup count.");
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
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingGroup",$teamMailingGroup,"Check for created object.");

    $searchParams = array(
      "team_id" => $team->id,
      "group_id" => $groupid,
    );
    $teamMailingGroup = new CRM_Team_BAO_TeamMailingGroup();
    $teamMailingGroup->copyValues($searchParams);
    $this->assertEquals(1,$teamMailingGroup->find(TRUE),"Check for TeamMailingGroup count.");
  }

  /**
   * Test that TeamMailingGroup is deleted by team.
   */
  public function testDeleteTeamMailingGroupByTeam() {
    $groupid = $this->groupCreate(array(
      "name"  => "Agileware Group 5",
      "title" => "Agileware Group 5 created",
      "description" => "Agileware Group 5 created",
    ));
    $team = $this->createTeam();
    $role = "draft";
    $params = array(
      "group_id" => $groupid,
      "team_id"  => $team->id,
      "role"     => $role
    );
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingGroup",$teamMailingGroup,"Check for created object.");

    $searchParams = array(
      "team_id" => $team->id
    );
    $teamMailingGroup = new CRM_Team_BAO_TeamMailingGroup();
    $teamMailingGroup->copyValues($searchParams);
    $this->assertEquals(1,$teamMailingGroup->delete(),"Check for TeamMailingGroup delete count.");
  }

  /**
   * Test that single TeamMailingGroup is deleted by team and group id.
   */
  public function testDeleteSingleTeamMailingGroup() {
    $groupid = $this->groupCreate(array(
      "name"  => "Agileware Group 6",
      "title" => "Agileware Group 6 created",
      "description" => "Agileware Group 6 created",
    ));
    $team = $this->createTeam();
    $role = "draft";
    $params = array(
      "group_id" => $groupid,
      "team_id"  => $team->id,
      "role"     => $role
    );
    $teamMailingGroup = CRM_Team_BAO_TeamMailingGroup::create($params);
    $this->assertInstanceOf("CRM_Team_DAO_TeamMailingGroup",$teamMailingGroup,"Check for created object.");

    $searchParams = array(
      "team_id" => $team->id,
      "group_id" => $groupid,
    );
    $teamMailingGroup = new CRM_Team_BAO_TeamMailingGroup();
    $teamMailingGroup->copyValues($searchParams);
    $this->assertEquals(1,$teamMailingGroup->delete(),"Check for TeamMailingGroup delete count.");
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
