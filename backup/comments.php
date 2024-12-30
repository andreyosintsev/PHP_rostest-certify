<?php/**
 * comments.php
 *
 * The template for displaying Comments.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
{
	die ('Please do not load this WordPress page directly.');
}
?>

<?php if ( post_password_required() ) { ?>
<!--password protected comments-->
<p class="nocomments">
  <?php _e('This post is password protected. Enter the password to view comments.', 'gopiplustheme') ?>
</p>
<?php return; } ?>

<?php $comments_by_type = &separate_comments($comments); ?>
<div id="comments">
	<?php if ( have_comments() ) : ?>
		<?php if ( ! empty($comments_by_type['comment']) ) : ?>
			<h3>
				<?php comments_number(__('No Responses', 'gopiplustheme'), __('One Response', 'gopiplustheme'), __('% Responses', 'gopiplustheme') );?>
				<?php _e('to', 'gopiplustheme') ?>&#8220;<?php the_title(); ?>&#8221;
			</h3>
			<ol class="commentlist">
				<?php wp_list_comments('avatar_size=35&callback=premiumstyle_custom_comment&type=comment'); ?>
			</ol>
			<div class="navigation">
				<div class="left">
					<?php previous_comments_link() ?>
				</div>
				<div class="right">
					<?php next_comments_link() ?>
				</div>
				<div class="clear"></div>
			</div>
  		<?php endif; ?>
		<?php if ( ! empty($comments_by_type['pings']) ) : ?>
			<h3 id="pings">
				<?php _e('Pingbacks/Trackbacks', 'gopiplustheme') ?>
			</h3>
			<ol class="pinglist">
				<?php wp_list_comments('type=pings&callback=premiumstyle_list_pings'); ?>
			</ol>
		<?php endif; ?>
		<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomments"><?php _e('Comments are closed.', 'gopiplustheme') ?></p>
		<?php endif; ?>
  <?php endif; ?>
</div>

<?php if ('open' == $post->comment_status) : ?>
	<div id="respond">
	<?php if ( get_option('comment_registration') && !$user_ID ) : // If registration required & not logged in. ?>
		<p>
			<?php _e('You must be', 'gopiplustheme') ?>
			<a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">
				<?php _e('logged in', 'gopiplustheme') ?>
			</a>
			<?php _e('to post a comment.', 'gopiplustheme') ?>
		</p>
	<?php else : // No registration required ?>
  		<?php
		$fields = array(
        'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" />' .
        '<label for="author">' . __('Name','gopiplustheme') . '</label>' . ( $req ? ' <span class="required">(Required)</span>' : '' ) . '</p>',
        'email' => '<p class="comment-form-email"><input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" />' .
        '<label for="email">' . __('E-Mail','gopiplustheme') . '</label>' . ( $req ? ' <span class="required">(Required)</span>' : '' ) . '</p>',
        'url' => '<p class="comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" />' .
        '<label for="url">' . __('Website','gopiplustheme') . '</label></p>',
		//'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		);
		$defaults = array('fields' => apply_filters('comment_form_default_fields', $fields));
		comment_form($defaults);
		?>
  <?php endif; // If registration required ?>
  <div class="fix"></div>
</div>
<?php endif; ?>