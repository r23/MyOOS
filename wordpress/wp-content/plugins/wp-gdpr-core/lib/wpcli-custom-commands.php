<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

class Comments_Command {

	public function __invoke( $args ) {
		//How many comments
		if ( isset( $args[0] ) && is_numeric( $args[0] ) && $args[0] > 1 ) {
			$amount_of_comments = $args[0];
		} else {
			$amount_of_comments = 1;
		}

		//Adres email of comments
		if ( isset( $args[1] ) ) {
			$comment_email = $args[1];
		} else {
			$comment_email = 'sejmaks@gmail.com';
		}

		//insert post
		$my_post = array(
			'post_title'   => 'super_title',
			'post_content' => 'content',
			'post_status'  => 'publish',
			'post_author'  => 1,
		);

		$id = wp_insert_post( $my_post );

		$data = array(
			'comment_post_ID'      => $id,
			'comment_author'       => 'admin',
			'comment_author_email' => 'sejmaks@gmail.com',
			'comment_author_url'   => 'http://',
			'comment_content'      => 'content here',
			'comment_type'         => '',
			'comment_parent'       => 0,
			'user_id'              => 1,
			'comment_author_IP'    => '127.0.0.1',
			'comment_agent'        => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
			'comment_date'         => time( 'sql' ),
			'comment_approved'     => 1,
		);

		for ( $i = 0; $i < $amount_of_comments; $i ++ ) {
			wp_insert_comment( $data );
		}

		\WP_CLI::success( 'Created ' . $amount_of_comments . ' comments with author_email ' . $comment_email . ' for post with ID ' . $id );
	}
}

\WP_CLI::add_command( 'comments insert', 'Comments_Command' );

class Foo_Command {
	public function __invoke( $args ) {
		// TODO: Implement __invoke() method.
		$count = count( get_posts() );
		\WP_CLI::success( $args[0] );
		\WP_CLI::success( $count );
	}
}

\WP_CLI::add_command( 'add page', 'Foo_Command' );
