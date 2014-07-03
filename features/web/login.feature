Feature: Login user system
  In order to access the platform
  As a cat user
  I want to be able to login and logout from it

Scenario: Not logged in users should not be able to access the homepage.
  Given I am not logged in
  When I go to the homepage
  Then I should be on "/login"

Scenario: Logged in user should be able to access the homepage.
  Given I am logged in
  When I go to the homepage
  Then I should be on the homepage

Scenario: Logged in user should not be able to access login page.
  Given I am logged in
  When I go to "/login"
  Then I should be on the homepage

Scenario: Logged in user should be able to logout.
  Given I am logged in
  When I follow "logout"
  Then I should be on "/login"