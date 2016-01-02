<?php
class BuddyPress extends GD_Test
{
    public function setUp()
    {
        parent::setUp();

        //skip test if already completed.
        if ($this->skipTest($this->getCurrentFileNumber(pathinfo(__FILE__, PATHINFO_FILENAME)), $this->getCompletedFileNumber())) {
            $this->markTestSkipped('Skipping '.pathinfo(__FILE__, PATHINFO_FILENAME).' since its already completed......');
            return;
        }
    }

    public function testBuddyPress()
    {
        $this->logInfo('Testing buddypress......');
        //make sure BuddyPress core plugin active
        $this->maybeAdminLogin(self::GDTEST_BASE_URL.'wp-admin/plugins.php');
        $this->waitForPageLoadAndCheckForErrors();

        $is_active = $this->byId("buddypress")->attribute('class');
        if (is_int(strpos($is_active, 'inactive'))) {
            //Activate Geodirectory buddypress
            $this->logInfo('Activating buddypress......');
            $this->url(self::GDTEST_BASE_URL.'wp-admin/plugins.php');
            $this->waitForPageLoadAndCheckForErrors();
            $this->hideAdminBar();
            $this->byXPath("//tr[@id='buddypress']//span[@class='activate']/a")->click();
            $this->waitForPageLoadAndCheckForErrors(20000);
            //go back to plugin page
            $this->url(self::GDTEST_BASE_URL.'wp-admin/plugins.php');
        }

        $is_active1 = $this->byId("buddypress")->attribute('class');
        $this->assertFalse( strpos($is_active1, 'inactive'), "buddypress plugin not active");

        //make sure BuddyPress Integration plugin active
        $is_active = $this->byId("geodirectory-buddypress-integration")->attribute('class');
        if (is_int(strpos($is_active, 'inactive'))) {
            //Activate Geodirectory buddypress integration
            $this->logInfo('Activating geodirectory buddypress integration......');
            $this->url(self::GDTEST_BASE_URL.'wp-admin/plugins.php');
            $this->waitForPageLoadAndCheckForErrors();
            $this->hideAdminBar();
            $this->byXPath("//tr[@id='geodirectory-buddypress-integration']//span[@class='activate']/a")->click();
            $this->waitForPageLoadAndCheckForErrors(20000);
            //go back to plugin page
            $this->url(self::GDTEST_BASE_URL.'wp-admin/plugins.php');
        }

        $is_active1 = $this->byId("geodirectory-buddypress-integration")->attribute('class');
        $this->assertFalse( strpos($is_active1, 'inactive'), "geodirectory buddypress integration plugin not active");


        //Make sure "Use BuddyPress registration form" checked.
        $this->url(self::GDTEST_BASE_URL.'wp-admin/admin.php?page=geodirectory&tab=geodir_buddypress&subtab=gdbuddypress_settings');
        $this->waitForPageLoadAndCheckForErrors();
        $this->prepareSession()->currentWindow()->maximize();

        $to_save = false;
        $is_checked_1 = $this->byId('geodir_buddypress_bp_register')->attribute('checked');
        if (!$is_checked_1) {
            $this->byId('geodir_buddypress_bp_register')->click();
            $to_save = true;
        }

        $is_checked_2 = $this->byId('geodir_buddypress_link_listing')->attribute('checked');
        if (!$is_checked_2) {
            $this->byId('geodir_buddypress_link_listing')->click();
            $to_save = true;
        }

        $is_checked_3 = $this->byId('geodir_buddypress_link_favorite')->attribute('checked');
        if (!$is_checked_3) {
            $this->byId('geodir_buddypress_link_favorite')->click();
            $to_save = true;
        }

        $is_checked_4 = $this->byId('geodir_buddypress_link_author')->attribute('checked');
        if (!$is_checked_4) {
            $this->byId('geodir_buddypress_link_author')->click();
            $to_save = true;
        }

        $is_checked_5 = $this->byId('geodir_buddypress_show_feature_image')->attribute('checked');
        if (!$is_checked_5) {
            $this->byId('geodir_buddypress_show_feature_image')->click();
            $to_save = true;
        }

        if ($to_save) {
            $this->byName('save')->click();
            $this->waitForPageLoadAndCheckForErrors();
        }


        $this->url(self::GDTEST_BASE_URL.'gd-login/');
        $this->waitForPageLoadAndCheckForErrors();
        $this->assertTrue( $this->isTextPresent("Sign In"), "No text found");
        $this->assertFalse( $this->isTextPresent("Sign Up Now"), "Sign up now text found in buddypress login");

        $this->byClassName('goedir-newuser-link')->click();
        $this->waitForPageLoadAndCheckForErrors();
        $this->assertTrue( $this->isTextPresent("Create an Account"), "Create an Account text not found");

        //register
        $this->byId('signup_username')->value('testuser123');
        $this->byId('signup_email')->value('testuser123@test.com');
        $this->byId('signup_password')->value('test12345');
        $this->byId('signup_password_confirm')->value('test12345');
        $this->byId('field_1')->value('Test User');
        $this->byId('signup_submit')->click();
        $this->waitForPageLoadAndCheckForErrors();
        $this->assertTrue( $this->isTextPresent("Check Your Email To Activate Your Account"), "BuddyPress Signup not successful");


        //check listings page for errors
        $this->url(self::GDTEST_BASE_URL.'members/admin/listings/');
        $this->waitForPageLoadAndCheckForErrors();
        $this->assertTrue( $this->isTextPresent("Places"), "Not in listings page");

        //check favorites page for errors
        $this->url(self::GDTEST_BASE_URL.'members/admin/favorites/');
        $this->waitForPageLoadAndCheckForErrors();
        $this->assertTrue( $this->isTextPresent("Places"), "Not in favorites page");

        //check reviews page for errors
        $this->url(self::GDTEST_BASE_URL.'members/admin/reviews/');
        $this->waitForPageLoadAndCheckForErrors();
        $this->assertTrue( $this->isTextPresent("Places"), "Not in reviews page");

        //test all buddypress pages and catch errors and warnings
        $this->url(self::GDTEST_BASE_URL.'members/');
        $this->waitForPageLoadAndCheckForErrors();

        $this->url(self::GDTEST_BASE_URL.'members/admin/');
        $this->waitForPageLoadAndCheckForErrors();

        $this->url(self::GDTEST_BASE_URL.'members/admin/profile/');
        $this->waitForPageLoadAndCheckForErrors();

        $this->url(self::GDTEST_BASE_URL.'members/admin/notifications/');
        $this->waitForPageLoadAndCheckForErrors();

        $this->url(self::GDTEST_BASE_URL.'members/admin/messages/');
        $this->waitForPageLoadAndCheckForErrors();

        $this->url(self::GDTEST_BASE_URL.'members/admin/friends/');
        $this->waitForPageLoadAndCheckForErrors();

        $this->url(self::GDTEST_BASE_URL.'members/admin/groups/');
        $this->waitForPageLoadAndCheckForErrors();

        $this->url(self::GDTEST_BASE_URL.'members/admin/listings/');
        $this->waitForPageLoadAndCheckForErrors();

    }

    public function tearDown()
    {
        if (!$this->skipTest($this->getCurrentFileNumber(pathinfo(__FILE__, PATHINFO_FILENAME)), $this->getCompletedFileNumber())) {
            //write current file number to completed.txt
            $CurrentFileNumber = $this->getCurrentFileNumber(pathinfo(__FILE__, PATHINFO_FILENAME));
            $completed = fopen("tests/selenium/completed.txt", "w") or die("Unable to open file!");
            fwrite($completed, $CurrentFileNumber);
        }
    }
}
?>