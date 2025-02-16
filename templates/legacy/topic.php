<?php
/**
 * Displays a topic.
 *
 * Available Variables:
 *
 * $course_id       : (int) ID of the course
 * $course      : (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 * $course_status   : Course Status
 * $has_access  : User has access to course or is enrolled.
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id         : (object) Current User ID
 * $logged_in       : (true/false) User is logged in
 * $current_user    : (object) Currently logged in user object
 * $quizzes         : (array) Quizzes Array
 * $post            : (object) The topic post object
 * $lesson_post     : (object) Lesson post object in which the topic exists
 * $topics      : (array) Array of Topics in the current lesson
 * $all_quizzes_completed : (true/false) User has completed all quizzes on the lesson Or, there are no quizzes.
 * $lesson_progression_enabled  : (true/false)
 * $show_content    : (true/false) true if lesson progression is disabled or if previous lesson and topic is completed.
 * $previous_lesson_completed   : (true/false) true if previous lesson is completed
 * $previous_topic_completed    : (true/false) true if previous topic is completed
 *
 * @since 2.1.0
 *
 * @package LearnDash\Templates\Legacy\Quiz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$activity_type = get_post_meta(get_the_ID(), 'inn-ld-activity-type', true);
$document_path = '';
$link_path = '';
$video_path = '';
$text_content = '';

if ($activity_type != 'Please select'){

    if ($activity_type == 'Downloadable file' && get_post_meta(get_the_ID(), 'inn-ld-document-path', true))
        $document_path = get_post_meta(get_the_ID(), 'inn-ld-document-path', true);
    else if ($activity_type == 'Link' && get_post_meta(get_the_ID(), 'inn-ld-external-link', true))
        $link_path = get_post_meta(get_the_ID(), 'inn-ld-external-link', true);
    else if ($activity_type == 'Youtube Video' && get_post_meta(get_the_ID(), 'inn-ld-video-link', true))
        $video_path = get_post_meta(get_the_ID(), 'inn-ld-video-link', true);
    else if ($activity_type == 'Plain text' && get_post_meta(get_the_ID(), 'inn-ld-plain-text', true))
        $text_content = get_post_meta(get_the_ID(), 'inn-ld-plain-text', true);

}

$completion_criteria = get_post_meta($post->ID, 'completion-criteria', true);

if ($completion_criteria == 'On click'){
    $innovade_btn_class = ' on-click-complete';
    
}else    
    $innovade_btn_class = '';

?>
<?php
/**
 * Topic Dots
 */
?>
<?php if ( ! empty( $topics ) ) : ?>
	<div id='learndash_topic_dots-<?php echo esc_attr( $lesson_id ); ?>' class="learndash_topic_dots type-dots">

		<b>
		<?php
		printf(
			// translators: placeholder: Topic.
			esc_html_x( '%s Progress:', 'placeholder: Topic', 'learndash' ),
			LearnDash_Custom_Label::get_label( 'topic' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
		);
		?>
		</b>

		<?php foreach ( $topics as $key => $topic ) : ?>
			<?php $completed_class = empty( $topic->completed ) ? 'topic-notcompleted' : 'topic-completed'; ?>
			<?php $completed_class .= ( $topic->ID === $post->ID ) ? ' ld-topic-current' : ''; ?>
			<a class='<?php echo esc_attr( $completed_class ); ?>' href='<?php echo esc_url( learndash_get_step_permalink( $topic->ID, $course_id ) ); ?>' title='<?php echo esc_html( $topic->post_title ); ?>'>
				<span title='<?php echo esc_html( $topic->post_title ); ?>'></span>
			</a>
		<?php endforeach; ?>



	</div>
<?php endif; ?>

<?php if ( ! empty( $course_id ) ) { ?>
<div id="learndash_back_to_lesson"><a href='<?php echo esc_url( learndash_get_step_permalink( $lesson_id, $course_id ) ); ?>'>&larr;
													<?php
														echo esc_html(learndash_get_label_course_step_back( get_post_type( $lesson_id ) ));
													?>
	</a></div>
<?php } ?>

<?php if ( $lesson_progression_enabled && ! $previous_topic_completed ) : ?>

	<span id="learndash_complete_prev_topic">
	<?php
		$previous_item = learndash_get_previous( $post );
	if ( empty( $previous_item ) ) {
		$previous_item = learndash_get_previous( $lesson_post );
	}

	if ( ( ! empty( $previous_item ) ) && ( $previous_item instanceof WP_Post ) ) {
		if ( 'sfwd-quiz' === $previous_item->post_type ) {
			echo sprintf(
				// translators: placeholder: quiz URL.
				esc_html_x( 'Please go back and complete the previous %s.', 'placeholder: quiz URL', 'learndash' ),
				'<a class="learndash-link-previous-incomplete" href="' . esc_url( learndash_get_step_permalink( $previous_item->ID, $course_id ) ) . '">' . esc_html( learndash_get_custom_label_lower( 'quiz' ) ) . '</a>'
			);

		} elseif ( 'sfwd-topic' === $previous_item->post_type ) {
			echo sprintf(
				// translators: placeholder: topic URL.
				esc_html_x( 'Please go back and complete the previous %s.', 'placeholder: topic URL', 'learndash' ),
				'<a class="learndash-link-previous-incomplete" href="' . esc_url( learndash_get_step_permalink( $previous_item->ID, $course_id ) ) . '">' . esc_html( learndash_get_custom_label_lower( 'topic' ) ) . '</a>'
			);
		} else {
			echo sprintf(
				// translators: placeholder: lesson URL.
				esc_html_x( 'Please go back and complete the previous %s.', 'placeholder: lesson URL', 'learndash' ),
				'<a class="learndash-link-previous-incomplete" href="' . esc_url( learndash_get_step_permalink( $previous_item->ID, $course_id ) ) . '">' . esc_html( learndash_get_custom_label_lower( 'lesson' ) ) . '</a>'
			);
		}
	} else {
		echo sprintf(
			// translators: placeholder: lesson.
			esc_html_x( 'Please go back and complete the previous %s.', 'placeholder: lesson', 'learndash' ),
			esc_html( learndash_get_custom_label_lower( 'lesson' ) )
		);
	}
	?>
	</span>
	<br />

<?php elseif ( $lesson_progression_enabled && ! $previous_lesson_completed ) : ?>

	<span id="learndash_complete_prev_lesson">
	<?php
		$previous_item = learndash_get_previous( $post );
	if ( empty( $previous_item ) ) {
		$previous_item = learndash_get_previous( $lesson_post );
	}

	if ( ( ! empty( $previous_item ) ) && ( $previous_item instanceof WP_Post ) ) {
		if ( 'sfwd-quiz' === $previous_item->post_type ) {
			echo sprintf(
				// translators: placeholder: quiz URL.
				esc_html_x( 'Please go back and complete the previous %s.', 'placeholder: quiz URL', 'learndash' ),
				'<a class="learndash-link-previous-incomplete" href="' . esc_url( learndash_get_step_permalink( $previous_item->ID, $course_id ) ) . '">' . esc_html( learndash_get_custom_label_lower( 'quiz' ) ) . '</a>'
			);

		} elseif ( 'sfwd-topic' === $previous_item->post_type ) {
			echo sprintf(
				// translators: placeholder: topic URL.
				esc_html_x( 'Please go back and complete the previous %s.', 'placeholder: topic URL', 'learndash' ),
				'<a class="learndash-link-previous-incomplete" href="' . esc_url( learndash_get_step_permalink( $previous_item->ID, $course_id ) ) . '">' . esc_html( learndash_get_custom_label_lower( 'topic' ) ) . '</a>'
			);
		} else {
			echo sprintf(
				// translators: placeholder: lesson URL.
				esc_html_x( 'Please go back and complete the previous %s.', 'placeholder: lesson URL', 'learndash' ),
				'<a class="learndash-link-previous-incomplete" href="' . esc_url( learndash_get_step_permalink( $previous_item->ID, $course_id ) ) . '">' . esc_html( learndash_get_custom_label_lower( 'lesson' ) ) . '</a>'
			);
		}
	} else {
		// translators: placeholder: lesson.
		echo sprintf( esc_html_x( 'Please go back and complete the previous %s.', 'placeholder: lesson', 'learndash' ), esc_html( learndash_get_custom_label_lower( 'lesson' ) ) );
	}
	?>
	</span>
	<br />

<?php endif; ?>

<?php if ( $show_content ) : ?>
	<?php if ( ( isset( $materials ) ) && ( ! empty( $materials ) ) ) : ?>
		<div id="learndash_topic_materials" class="learndash_topic_materials">
			<h4>
			<?php
			// translators: placeholder: Topic.
			printf( esc_html_x( '%s Materials', 'placeholder: Topic', 'learndash' ), LearnDash_Custom_Label::get_label( 'topic' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
			?>
			</h4>
			<p><?php echo esc_html($materials); ?></p>
		</div>
	<?php endif; ?>

	<div class="learndash_content"><?php echo wp_kses($content, wp_kses_allowed_html('post')); ?>
		<?php 
		/**
         *  Innovade Learndash activities layout
         * */ 
        ?>
        <div class="ld-content-actions">
            <?php    if ($document_path){ // Downloadable files?>
                <a class="ld-button<?php echo esc_attr($innovade_btn_class); ?>" href="<?php echo esc_url($document_path); ?>" target="_blank" data-id="<?php echo esc_attr(get_the_ID()); ?>"><?php _e('Download document'); ?></a>
            <?php } ?>
			<?php    if ($link_path){ // External link
						
						$url = parse_url($link_path);
						if (empty($url['scheme'])) // Check if link starts with http or https
							$link_path = 'https://' . $link_path;
			?>
                <a class="ld-button<?php echo esc_attr($innovade_btn_class); ?>" href="<?php echo esc_url($link_path); ?>" target="_blank" data-id="<?php echo esc_attr(get_the_ID()); ?>"><?php _e('View link'); ?></a>
            <?php } ?>
			<?php    if ($text_content){ // Text content ?>
				<p>
					<?php echo esc_html($text_content); ?>
				</p>
            <?php } ?>
			<?php    if ($video_path){ // Youtube video 
				$videoUrl = substr($video_path, strrpos($video_path, '=') + 1);	
				?>
				<iframe class="ld-iframe" width="560" height="315" src="https://www.youtube.com/embed/<?php echo esc_url($videoUrl); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen>

				</iframe>
            <?php } ?>
        </div>

	</div>

	<?php if ( ! empty( $quizzes ) ) : ?>
		<div id="learndash_quizzes" class="learndash_quizzes">
			<div id="quiz_heading"><span><?php echo LearnDash_Custom_Label::get_label( 'quizzes' ); ?></span><span class="right"><?php esc_html_e( 'Status', 'learndash' ); ?></span></div> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output ?>

			<div id="quiz_list" class="quiz_list">
			<?php foreach ( $quizzes as $quiz ) : ?>
				<div id='post-<?php echo esc_attr( $quiz['post']->ID ); ?>' class='<?php echo esc_attr( $quiz['sample'] ); ?>'>
					<div class="list-count"><?php echo esc_html( $quiz['sno'] ); ?></div>
					<h4>
						<a class='<?php echo esc_attr( $quiz['status'] ); ?>' href='<?php echo esc_url( $quiz['permalink'] ); ?>'><?php echo $quiz['post']->post_title; ?></a>
					</h4>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ( learndash_lesson_hasassignments( $post ) ) && ( ! empty( $user_id ) ) ) : // cspell:disable-line. ?>
		<?php
			$ret = SFWD_LMS::get_template(
				'learndash_lesson_assignment_uploads_list.php',
				array(
					'course_step_post' => $post,
					'user_id'          => $user_id,
				)
			);
			echo wp_kses($ret, wp_kses_allowed_html('post'));
		?>
	<?php endif; ?>


	<?php
	/**
	 * Show Mark Complete Button
	 */
	?>
	<?php if ( $all_quizzes_completed && $logged_in && ! empty( $course_id ) && $completion_criteria !== 'On click') : ?>
		<?php
		echo '<br />' . learndash_mark_complete(
			$post,
			array(
				'form'   => array(
					'id' => 'sfwd-mark-complete',
				),
				'button' => array(
					'id' => 'learndash_mark_complete_button',
				),
				'timer'  => array(
					'id' => 'learndash_timer',
				),
			)
		);
		?>
	<?php endif; ?>

<?php endif; ?>

<?php
$ret = SFWD_LMS::get_template(
	'learndash_course_steps_navigation.php',
	array(
		'course_id'        => $course_id,
		'course_step_post' => $post,
		'user_id'          => $user_id,
		'course_settings'  => isset( $course_settings ) ? $course_settings : array(),
	)
);
echo wp_kses($ret, wp_kses_allowed_html('post'));