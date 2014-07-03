Feature: Cats

Scenario: Returning a collection of Cats
  When I request "GET /cats"
  Then I get a "200" response
  And scope into "catLover" cat
  And the properties exist:
  """
  name
  description
  img
  gallery
  interactions.miau
  interactions.prrr
  interactions.fzzzz
  """
  And name should be a "string"
  And gallery should be a "list"

Scenario: Returning only one cat
  When I request "GET /cats/catLover"
  Then I get a "200" response
  And the properties exist:
  """
  name
  description
  img
  gallery
  interactions.miau
  interactions.prrr
  interactions.fzzzz
  """
  And name should be a "string"
  And gallery should be a "list"
  And interactions should be an "object"

Scenario: Returning only one cat that does not exist.
  When I request "GET /cats/cat-not-existing"
  Then I get a "404" response