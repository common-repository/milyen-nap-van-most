=== Milyen nap van most? ===
Contributors: gaba, vbali
Link: http://vbali.com/milyen-nap-van-most/
Tags: comments, spam
Requires at least: 2.0.2
Tested up to: 3.0.0
Stable tag: 1.6.1

A simple question-based comment anti-spam plugin for WordPress, only intended for Hungarian blogs, as it displays questions in Hungarian.

== Description ==

The plugin will display a question in your comment form. This question must be answered correctly otherwise the comment will be rejected. The question and the answer is in Hungarian, so it is intended for blogs that receive comments only from people speaking Hungarian.

== Frequently Asked Questions ==

Q: How can I alter the text of the question and other messages?

A: You have to edit the source code of the plugin.

== Installation ==

1. Upload `milyen-nap-van-most.php` to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Make sure the tag ` <?php do_action('comment_form', $post->ID); ?>` is included in your `comments.php` file, preferably before the tag `<input name="submit" type="submit" id="submit" ...`

== Screenshots ==

1. A new field with a question will appear on the comment form if the user isn't logged in.
