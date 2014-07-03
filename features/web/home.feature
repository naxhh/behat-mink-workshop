Feature: Home page important information

Scenario: Welcome message
  Given I am logged in
  When I go to the homepage
  Then I should see "Miau!" in the ".jumbotron" element

Scenario: Current user should not be listed in the kitties list.
  Given I am logged in as "kitty69"
  When I go to the homepage
  Then I should not see "kitty69" in the ".row" element

Scenario: Interactions with current user should be displayed.
  Given I am logged in
  # And I have one miau notification
  When I go to the homepage
  Then I should see text matching "miau on you!"