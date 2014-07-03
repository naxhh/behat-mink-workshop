<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

class WebContext extends MinkContext
{
    /**
     * @Given I am not logged in
     */
    public function iAmNotLoggedIn()
    {
    }

    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        $this->iAmLoggedInAs( 'kitty69' );
    }

    /**
     * @Given I am logged in as :user
     */
    public function iAmLoggedInAs($user)
    {
        $this->getSession()->visit($this->locatePath('/login'));
        $page = $this->getSession()->getPage();

        $element = $page->fillField('username', $user);

        $element = $page->fillField('password', $user);

        $element = $page->pressButton('login');
    }

    /**
     * @Then /^I wait for the warning box to appear$/
     */
    public function iWaitForTheSuggestionBoxToAppear()
    {
        $this->getSession()->wait(1000, "$('#messages').length > 0");
    }
}
