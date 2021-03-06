<?php
/**
 * Blog Avatar Endpoints Tests.
 *
 * @package BuddyPress
 * @subpackage BP_REST
 * @group blog-avatar
 */
class BP_Test_REST_Attachments_Blog_Avatar_Endpoint extends WP_Test_REST_Controller_Testcase {

	public function setUp() {
		parent::setUp();

		$this->bp_factory   = new BP_UnitTest_Factory();
		$this->endpoint     = new BP_REST_Attachments_Blog_Avatar_Endpoint();
		$this->bp           = new BP_UnitTestCase();
		$this->endpoint_url = '/' . bp_rest_namespace() . '/' . bp_rest_version() . '/' . buddypress()->blogs->id . '/';

		if ( ! $this->server ) {
			$this->server = rest_get_server();
		}
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function test_register_routes() {
		$routes   = $this->server->get_routes();
		$endpoint = $this->endpoint_url . '(?P<id>[\d]+)/avatar';

		// Single.
		$this->assertArrayHasKey( $endpoint, $routes );
		$this->assertCount( 1, $routes[ $endpoint ] );
	}

	/**
	 * @group get_items
	 */
	public function test_get_items() {
		$this->markTestSkipped();
	}

	/**
	 * @group get_item
	 */
	public function test_get_item() {
		$this->markTestSkipped();

		$u = $this->bp_factory->user->create();

		$this->bp->set_current_user( $u );

		$blog_id = $this->bp_factory->blog->create();

		$request = new WP_REST_Request( 'GET', sprintf( $this->endpoint_url . '%d/avatar', $blog_id ) );
		$request->set_param( 'context', 'view' );

		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'bp_rest_attachments_blog_avatar_no_image', $response, 500 );
	}

	/**
	 * @group get_item
	 */
	public function test_get_item_invalid_user_id() {
		$this->markTestSkipped();

		$blog_id = $this->bp_factory->blog->create();
		$request = new WP_REST_Request( 'GET', sprintf( $this->endpoint_url . '%d/avatar', $blog_id ) );

		$request->set_file_params(
			[
				'user_id' => REST_TESTS_IMPOSSIBLY_HIGH_NUMBER,
			]
		);
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'bp_rest_blog_avatar_get_item_user_failed', $response, 500 );
	}

	/**
	 * @group get_item
	 */
	public function test_get_item_invalid_blog_id() {
		$this->markTestSkipped();

		$request  = new WP_REST_Request( 'GET', sprintf( $this->endpoint_url . '%d/avatar', REST_TESTS_IMPOSSIBLY_HIGH_NUMBER ) );
		$response = rest_get_server()->dispatch( $request );
		$this->assertErrorResponse( 'bp_rest_blog_invalid_id', $response, 404 );
	}

	/**
	 * @group create_item
	 */
	public function test_create_item() {
		$this->markTestSkipped();
	}

	/**
	 * @group update_item
	 */
	public function test_update_item() {
		$this->markTestSkipped();
	}

	/**
	 * @group delete_item
	 */
	public function test_delete_item() {
		$this->markTestSkipped();
	}

	/**
	 * @group prepare_item
	 */
	public function test_prepare_item() {
		$this->markTestSkipped();
	}

	public function test_get_item_schema() {
		if ( ! is_multisite() ) {
			$this->markTestSkipped();
		}

		$blog_id = $this->bp_factory->blog->create();

		// Single.
		$request    = new WP_REST_Request( 'OPTIONS', sprintf( $this->endpoint_url . '%d/avatar', $blog_id ) );
		$response   = $this->server->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];

		$this->assertEquals( 2, count( $properties ) );
		$this->assertArrayHasKey( 'full', $properties );
		$this->assertArrayHasKey( 'thumb', $properties );
	}

	public function test_context_param() {
		if ( ! is_multisite() ) {
			$this->markTestSkipped();
		}

		$blog_id = $this->bp_factory->blog->create();

		// Single.
		$request  = new WP_REST_Request( 'OPTIONS', sprintf( $this->endpoint_url . '%d/avatar', $blog_id ) );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$this->assertNotEmpty( $data );
	}
}
