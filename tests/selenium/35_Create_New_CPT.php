<?php
class CreateNewCPT extends GD_Test
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCreateNewCPT()
    {
        //make sure custom post types plugin active
        $this->maybeAdminLogin(self::GDTEST_BASE_URL.'wp-admin/plugins.php');
        $this->waitForPageLoadAndCheckForErrors();
        $is_active = $this->byId("geodirectory-custom-post-types")->attribute('class');
        $this->assertFalse( strpos($is_active, 'inactive'), "custom post types plugin not active");
        if (strpos($is_active, 'inactive')) {
            return;
        }

        $this->url(self::GDTEST_BASE_URL.'wp-admin/admin.php?page=geodirectory&tab=geodir_manage_custom_posts');
        $this->waitForPageLoadAndCheckForErrors();
        if ($this->isTextPresent("gd_hotel")) {
            echo "Hotel post type already found. Please delete it first";
            return;
        }

        $this->url(self::GDTEST_BASE_URL.'wp-admin/admin.php?page=geodirectory&tab=geodir_manage_custom_posts&action=cp_addedit');
        $this->waitForPageLoadAndCheckForErrors();
        $this->assertTrue( $this->isTextPresent("Post Type"), "Post Type text not found");
        $this->byId('geodir_custom_post_type')->value('hotel');
        $this->byId('geodir_listing_slug')->value('hotels');
        $this->byId('geodir_listing_order')->value('10');
        $this->byId('geodir_name')->value('Hotels');
        $this->byId('geodir_singular_name')->value('Hotel');
        $this->byName('geodir_save_post_type')->click();
        $this->waitForPageLoadAndCheckForErrors();
    }
}
?>