<?php
/**
 *  Innovade Learndash Activities
 *  
 *  Topic page - admin fields
 * 
 */
class InnLearndashActivitiesMetaBox{

	private $screen = array(
		'sfwd-topic'
                        
	);

	private $meta_fields = array(
                array(
                    'label' => 'Activity Type',
                    'id' => 'inn-ld-activity-type',
                    'type' => 'select',
                    'options' => array(
                        'Please select',
                        'Downloadable file',
                        'Link',
                        'Youtube Video',
                        'Plain text',
                    )
                ),
    
                array(
                    'label' => 'Document path',
                    'id' => 'inn-ld-document-path',
                    'type' => 'media',
                    'returnvalue' => 'url',
                    'instructions' => 'Upload your downloadable file',
                ),
    
                array(
                    'label' => 'External Link',
                    'id' => 'inn-ld-external-link',
                    'type' => 'text',
                ),
    
                array(
                    'label' => 'Video link',
                    'id' => 'inn-ld-video-link',
                    'type' => 'text',
                ),
    
                array(
                    'label' => 'Plain text',
                    'id' => 'inn-ld-plain-text',
                    'type' => 'textarea',
                ),
                array(
                    'label' => 'Completion Criteria',
                    'id' => 'completion-criteria',
                    'type' => 'select',
                    'options' => array(
                        'Please select',
                        'Manual',
                        'On click',
                    )
                )

	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'admin_footer', array( $this, 'media_fields' ) );
        add_action( 'admin_footer', array( $this, 'inn_conditional_logic' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}

    public function inn_conditional_logic(){
        ?><script>
            jQuery(document).ready(function($){

                $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                if ($('#inn-ld-activity-type option:selected').attr('value') == 'Link'){
                    $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                    $('tr#inn-ld-external-link').css('display', 'table-row');
                    $('tr#completion-criteria').css('display', 'table-row');
                }
                else if ($('#inn-ld-activity-type option:selected').attr('value') == 'Downloadable file'){
                    $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                    $('tr#inn-ld-document-path').css('display', 'table-row');  
                    $('tr#completion-criteria').css('display', 'table-row');
                }
                    
                else if ($('#inn-ld-activity-type option:selected').attr('value') == 'Youtube Video'){
                    $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                    $('tr#inn-ld-video-link').css('display', 'table-row');
                }
                    
                else if ($('#inn-ld-activity-type option:selected').attr('value') == 'Plain text'){
                    $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                    $('tr#inn-ld-plain-text').css('display', 'table-row');
                }
                else{
                    $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                }

                $('select#inn-ld-activity-type').on('change', function(){
                    if ($('option:selected', this).attr('value') == 'Link'){
                        $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                        $('tr#inn-ld-external-link').css('display', 'table-row');
                        $('tr#completion-criteria').css('display', 'table-row');
                    }
                    else if ($('option:selected', this).attr('value') == 'Downloadable file'){
                        $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                        $('tr#inn-ld-document-path').css('display', 'table-row');  
                        $('tr#completion-criteria').css('display', 'table-row');
                    }
                        
                    else if ($('option:selected', this).attr('value') == 'Youtube Video'){
                        $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                        $('tr#inn-ld-video-link').css('display', 'table-row');
                    }
                        
                    else if ($('option:selected', this).attr('value') == 'Plain text'){
                        $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                        $('tr#inn-ld-plain-text').css('display', 'table-row');
                    }else{
                        $('tr#inn-ld-external-link, tr#inn-ld-document-path, tr#inn-ld-video-link, tr#inn-ld-plain-text, tr#completion-criteria').css('display', 'none');
                    }

                });
            });

        </script>
    <?php
    }

	public function add_meta_boxes() {
		foreach ( $this->screen as $single_screen ) {
			add_meta_box(
				'InnLearndashActivities',
				__( 'Innovade Learndash Activities', '' ),
				array( $this, 'meta_box_callback' ),
				$single_screen,
				'normal',
				'default'
			);
		}
	}

	public function meta_box_callback( $post ) {
		wp_nonce_field( 'InnLearndashActivities_data', 'InnLearndashActivities_nonce' );
		$this->field_generator( $post );
	}

    public function media_fields() {
        ?><script>
            jQuery(document).ready(function($){
                if ( typeof wp.media !== 'undefined' ) {
                    var _custom_media = true,
                    _orig_send_attachment = wp.media.editor.send.attachment;
                    $('.new-media').click(function(e) {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(this);
                        var id = button.attr('id').replace('_button', '');
                        _custom_media = true;
                            wp.media.editor.send.attachment = function(props, attachment){
                            if ( _custom_media ) {
                                if ($('input#' + id).data('return') == 'url') {
                                    $('input#' + id).val(attachment.url);
                                } else {
                                    $('input#' + id).val(attachment.id);
                                }
                            } else {
                                return _orig_send_attachment.apply( this, [props, attachment] );
                            };
                        }
                        wp.media.editor.open(button);
                        return false;
                    });
                    $('.add_media').on('click', function(){
                        _custom_media = false;
                    });
                    $('.remove-media').on('click', function(){
                        var parent = $(this).parents('td');
                        parent.find('input[type="text"]').val('');
                        parent.find('div').css('background-image', 'url()');
                    });
                }
            });
        </script><?php
    }

	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . esc_attr($meta_field['id']) . '">' . esc_html($meta_field['label']) . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );
			if ( empty( $meta_value ) ) {
				if ( isset( $meta_field['default'] ) ) {
					$meta_value = $meta_field['default'];
				}
			}
			switch ( $meta_field['type'] ) {
                case 'media':
                    $meta_url = '';
                        if ($meta_value) {
                            if ($meta_field['returnvalue'] == 'url') {
                                $meta_url = esc_url($meta_value);
                            } else {
                                $meta_url = esc_url(wp_get_attachment_url($meta_value));
                            }
                        }
                    $input = sprintf(
                        '<input style="display: inline-block;width: 100%%;" id="%s" name="%s" type="text" value="%s" data-return="%s" readonly>
                        <div id="preview%s"></div><input style="width: 19%%;margin-right:5px;" class="button new-media" id="%s_button" name="%s_button" type="button" value="Select" /><input style="width: 19%%;" class="button remove-media" id="%s_buttonremove" name="%s_buttonremove" type="button" value="Clear" />',
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['id']),
                        esc_attr($meta_value),
                        $meta_field['returnvalue'],
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['id'])
                    );
                    break;


                case 'select':
                    $input = sprintf(
                        '<select id="%s" name="%s">',
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['id'])
                    );
                    foreach ( $meta_field['options'] as $key => $value ) {
                        $meta_field_value = !is_numeric( $key ) ? $key : $value;
                        $input .= sprintf(
                            '<option %s value="%s">%s</option>',
                            $meta_value === $meta_field_value ? 'selected' : '',
                            esc_attr($meta_field_value),
                            esc_html($value)
                        );
                    }
                    $input .= '</select>';
                    break;

                case 'textarea':
                    $input = sprintf(
                        '<textarea style="" id="%s" name="%s" rows="5" cols="100">%s</textarea>',
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['id']),
                        esc_html($meta_value)
                    );
                break;

                default:
                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                        $meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['id']),
                        esc_attr($meta_field['type']),
                        esc_attr($meta_value)
                    );
			}
			$output .= $this->format_rows( $label, $input, $meta_field['id'] );
		}
       
        $allowed_html = wp_kses_allowed_html('post');
         //allow select
        $allowed_html['select']['id'] = true;
        $allowed_html['select']['name'] = true;
         //allow option
        $allowed_html['option']['selected'] = true; 
        $allowed_html['option']['value'] = true; 
         //allow input 
        $allowed_html['input']['id'] = true; 
        $allowed_html['input']['style'] = true; 
        $allowed_html['input']['name'] = true; 
        $allowed_html['input']['type'] = true; 
        $allowed_html['input']['data-return'] = true; 
        $allowed_html['input']['readonly'] = true; 
        $allowed_html['input']['class'] = true;
        $allowed_html['input']['value'] = true; 
		echo '<table class="form-table"><tbody>' . wp_kses(
            $output,
            $allowed_html
        ) . '</tbody></table>';
       
        //echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	public function format_rows( $label, $input, $meta_field ) {
		return '<tr id="'.esc_attr($meta_field).'"><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}

	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['InnLearndashActivities_nonce'] ) )
			return $post_id;
		$nonce = $_POST['InnLearndashActivities_nonce'];
		if ( !wp_verify_nonce( $nonce, 'InnLearndashActivities_data' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_post_meta( $post_id, $meta_field['id'], sanitize_text_field($_POST[ $meta_field['id'] ]) );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $meta_field['id'], '0' );
			}
		}
	}
}

if (class_exists('InnLearndashActivitiesMetabox')) {
	new InnLearndashActivitiesMetabox;
};



