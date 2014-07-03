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

