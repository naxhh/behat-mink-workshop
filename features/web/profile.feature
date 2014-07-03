Feature: Profile of a kitty

Scenario: Go from homepage to Profile
  Given I am logged in
  When I follow "bigNails profile"
  Then I should be on "/kitty/bigNails"

Scenario: Kitty basic information is present
  Given I am logged in
  When I go to "/kitty/bigNails"
  Then I should see "bigNails" in the "#profile-brief" element