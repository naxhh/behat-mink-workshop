Feature: Interaction between kitties
  In order to comunicate with other cats
  As a cat
  I want to be able to interact with cool kitties

Scenario: Send a hi! to other kitty
  Given I am logged in
  And I am on "/kitty/bigNails"
  When I follow "miau"
  Then I should see "You said hi!"