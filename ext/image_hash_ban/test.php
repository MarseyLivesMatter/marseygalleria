<?php declare(strict_types=1);
class ImageBanTest extends ShimmiePHPUnitTestCase
{
    private $hash = "feb01bab5698a11dd87416724c7a89e3";

    public function testBan()
    {
        $this->log_in_as_admin();

        // Post image
        $image_id = $this->post_image("tests/pbx_screenshot.jpg", "pbx");
        $page = $this->get_page("post/view/$image_id");
        $this->assertEquals(200, $page->code);

        // Ban & delete
        send_event(new AddImageHashBanEvent($this->hash, "test hash ban"));
        send_event(new ImageDeletionEvent(Image::by_id($image_id), true));

        // Check deleted
        $page = $this->get_page("post/view/$image_id");
        $this->assertEquals(404, $page->code);

        // Can't repost
        try {
            $this->post_image("tests/pbx_screenshot.jpg", "pbx");
            $this->assertTrue(false);
        } catch (UploadException $e) {
            $this->assertTrue(true);
        }

        // Remove ban
        send_event(new RemoveImageHashBanEvent($this->hash));

        // Can repost
        $image_id = $this->post_image("tests/pbx_screenshot.jpg", "pbx");
        $page = $this->get_page("post/view/$image_id");
        $this->assertEquals(200, $page->code);
    }

    public function onNotSuccessfulTest(\Throwable $t): void {
        send_event(new RemoveImageHashBanEvent($this->hash));
        parent::onNotSuccessfulTest($t); // TODO: Change the autogenerated stub
    }
}
