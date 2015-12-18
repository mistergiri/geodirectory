<?php
class AuthorPage extends GD_Test
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testAuthorPage()
    {
        $this->url(self::GDTEST_BASE_URL.'author/admin/?geodir_dashbord=true&stype=gd_place');
        $this->waitForPageLoadAndCheckForErrors();
        $this->assertTrue( $this->isTextPresent("Places by"), "Not in Author page");
    }
}
?>